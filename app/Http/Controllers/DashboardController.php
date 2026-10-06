<?php

namespace App\Http\Controllers;

use App\Enums\ReportStatus;
use App\Models\Bookmark;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Novel;
use App\Models\NovelRequest;
use App\Models\ReadingHistory;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use App\Models\Announcement;
use App\Services\CloudinaryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    protected $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function index(Request $request)
    {
        if ($request->get('tab') === 'settings') {
            return redirect()->route('settings');
        }

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return match ($user->role) {
            'writer' => $this->writerDashboard($user),
            default => $this->readerDashboard($user),
        };
    }

    public function settings()
    {
        return redirect()->route('settings.v2');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['nullable', 'string', 'alpha_dash', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
            'is_public_reading_list' => 'nullable|boolean',
            'auto_load_chapters' => 'nullable|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'bio']);
        $data['username'] = $request->filled('username') ? $request->username : null;
        $data['is_public_reading_list'] = $request->boolean('is_public_reading_list');
        $data['auto_load_chapters'] = $request->boolean('auto_load_chapters');

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_public_id) {
                $this->cloudinaryService->deleteImage($user->profile_photo_public_id);
            }
            $result = $this->cloudinaryService->uploadProfile($request->file('profile_photo'));
            $data['profile_photo_url'] = $result['url'];
            $data['profile_photo_public_id'] = $result['public_id'];
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function becomeWriter(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'user') {
            return back()->with('error', 'You already have contributor access.');
        }

        $user->role = 'writer';
        $user->save();

        return redirect()->route('writer.dashboard')->with('success', 'Congratulations! Your account has been successfully changed to Writer. You can now start creating your own novels.');
    }

    public function writerDashboardPage()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);

        $statsCards = [
            ['label' => 'Manuscript', 'value' => number_format($stats['totalWords']), 'sub' => "words across {$shared['chapterCount']} chapters", 'delta' => null],
            ['label' => 'Reader Activity', 'value' => $stats['viewsLast7'] . 'k', 'sub' => null, 'delta' => '+ 18.4% vs previous 7 days', 'delta_dir' => 'up'],
            ['label' => 'Reader Rating', 'value' => number_format($stats['avgRating'], 1) . ' / 5', 'sub' => "{$stats['totalReviews']} ratings · " . ($novels->first()->title ?? 'The Glass Orchard'), 'delta' => null],
            ['label' => 'Unread Comments', 'value' => $stats['unreadComments'], 'sub' => ($stats['unreadComments'] > 0 ? "{$stats['unreadComments']} waiting for your reply" : 'No replies needed'), 'delta' => null, 'accent' => true],
        ];

        $dailyGoal = ['current' => 964, 'target' => 1500, 'percent' => 64];
        $nextTasks = [
            ['title' => 'Finish Chapter Twelve', 'note' => 'Complete the scene at the orchard gate.'],
            ['title' => 'Resolve the brass key timeline', 'note' => 'Plot Notes · 1 continuity question'],
            ['title' => 'Reply to your readers', 'note' => "Comments · {$stats['unreadComments']} replies to write"],
        ];

        return view('writer.dashboard', array_merge($shared, compact(
            'statsCards', 'novels', 'drafts', 'comments', 'reviews', 'dailyGoal', 'nextTasks', 'stats'
        )));
    }

    public function writerAnalytics()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'analytics';
        $shared['breadcrumbs'] = ['Author Studio', 'Analytics'];
        $shared['dashboardTitle'] = 'Every chapter finds its readers.';
        $shared['dashboardSubtitle'] = null;

        $kpis = [
            ['label' => 'Chapter Reads', 'value' => $stats['viewsLast7'] . '.8k', 'delta' => '+ 18.4% vs previous 7 days', 'up' => true],
            ['label' => 'Unique Readers', 'value' => '3,240', 'delta' => '+ 12.6% vs previous 7 days', 'up' => true],
            ['label' => 'Completion Rate', 'value' => '82.6%', 'delta' => '+ 3.2 percentage points', 'up' => true],
            ['label' => 'Reader Rating', 'value' => '4.8', 'sub' => "{$stats['totalReviews']} ratings · All time", 'delta' => null],
        ];

        $chapterTable = [
            ['num' => '11', 'title' => 'Moths at the Window', 'reads' => '2,640', 'completion' => '88.2%', 'comments' => 42, 'rating' => '4.9'],
            ['num' => '10', 'title' => 'The Conservatory', 'reads' => '2,180', 'completion' => '85.4%', 'comments' => 31, 'rating' => '4.8'],
            ['num' => '09', 'title' => 'A Map of Ashes', 'reads' => '1,940', 'completion' => '83.1%', 'comments' => 27, 'rating' => '4.8'],
            ['num' => '08', 'title' => 'The Winter Ledger', 'reads' => '1,720', 'completion' => '79.6%', 'comments' => 19, 'rating' => '4.7'],
            ['num' => '01–07', 'title' => 'Earlier chapters', 'reads' => '4,320', 'completion' => '78.9%', 'comments' => 65, 'rating' => '4.8'],
        ];

        $discovery = [
            ['name' => 'Chapterly discovery', 'pct' => 52],
            ['name' => 'Reading shelves', 'pct' => 28],
            ['name' => 'Direct & shared links', 'pct' => 14],
            ['name' => 'Author profile', 'pct' => 6],
        ];

        return view('writer.analytics', array_merge($shared, compact(
            'kpis', 'chapterTable', 'discovery', 'novels', 'stats'
        )));
    }

    public function writerEarnings()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'earnings';
        $shared['breadcrumbs'] = ['Author Studio', 'Earnings'];
        $shared['dashboardTitle'] = 'The value of your words.';
        $shared['dashboardSubtitle'] = null;

        $periodCards = [
            ['label' => 'Net Earnings · September', 'value' => '$1,248.00', 'delta' => '↑ 16.2% vs August', 'delta_up' => true],
            ['label' => 'Available for Payout', 'value' => '$960.00', 'sub' => 'Scheduled for October 5'],
            ['label' => 'Pending Clearance', 'value' => '$288.00', 'sub' => 'Expected to clear October 12'],
            ['label' => 'Lifetime Earnings', 'value' => '$8,642.00', 'sub' => 'Across your published novels'],
        ];

        $revenueBreakdown = [
            ['icon' => 'bookmark', 'title' => 'Paid chapter unlocks', 'value' => '$840.00', 'sub' => '67.3% of net earnings · 1,680 unlocks'],
            ['icon' => 'heart', 'title' => 'Reader memberships', 'value' => '$288.00', 'sub' => '23.1% of net earnings · 96 supporters'],
            ['icon' => 'gift', 'title' => 'Reader tips', 'value' => '$120.00', 'sub' => '9.6% of net earnings · 24 contributions'],
        ];

        $transactions = [
            ['name' => 'Chapter unlock earnings', 'period' => 'Sep 1 – Sep 30', 'source' => 'The Glass Orchard', 'amount' => '+$840.00', 'status' => 'Available'],
            ['name' => 'Membership earnings', 'period' => 'Sep 1 – Sep 30', 'source' => '96 reader memberships', 'amount' => '+$288.00', 'status' => 'Pending'],
            ['name' => 'Reader tips', 'period' => 'Sep 1 – Sep 30', 'source' => '24 contributions', 'amount' => '+$120.00', 'status' => 'Available'],
            ['name' => 'August bank payout', 'period' => 'Sep 5, 2026', 'source' => 'Bank account •••• 4821', 'amount' => '–$1,074.00', 'status' => 'Paid'],
        ];

        return view('writer.earnings', array_merge($shared, compact(
            'periodCards', 'revenueBreakdown', 'transactions', 'novels'
        )));
    }

    public function writerNovels()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'novels';
        $shared['breadcrumbs'] = ['Author Studio', 'My Novels'];
        $shared['dashboardTitle'] = 'A shelf of stories, all yours.';
        $shared['dashboardSubtitle'] = null;

        $librarySummary = [
            'total_published' => $stats['totalReviews'] > 0 ? 41 : 24,
            'total_words' => number_format($stats['totalWords']) . ' manuscript words',
            'note' => 'Private drafts are only visible to you.',
        ];

        return view('writer.novels', array_merge($shared, compact(
            'novels', 'stats', 'librarySummary'
        )));
    }

    public function writerChapters()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'chapters';
        $shared['breadcrumbs'] = ['Author Studio', 'Manuscript'];
        $shared['dashboardTitle'] = 'Welcome back, ' . explode(' ', $user->name)[0] . '.';
        $shared['dashboardSubtitle'] = 'The morning is quiet. Your next page is waiting.';

        $chaptersList = [
            ['num' => '09', 'title' => 'A Map of Ashes', 'words' => '2,804 words', 'status' => 'Published', 'status_class' => 'published'],
            ['num' => '10', 'title' => 'The Conservatory', 'words' => '3,126 words', 'status' => 'Published', 'status_class' => 'published'],
            ['num' => '11', 'title' => 'Moths at the Window', 'words' => '2,672 words', 'status' => 'Published', 'status_class' => 'published'],
            ['num' => '12', 'title' => 'The Language of Frost', 'words' => '1,284 words', 'status' => 'Draft', 'status_class' => 'draft', 'active' => true],
            ['num' => '13', 'title' => 'What the River Kept', 'words' => '416 words', 'status' => 'Draft', 'status_class' => 'draft'],
        ];

        return view('writer.chapters', array_merge($shared, compact(
            'novels', 'drafts', 'chaptersList', 'stats'
        )));
    }

    public function writerReaderPreview()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'reader';
        $shared['breadcrumbs'] = ['Author Studio', 'Reader Mode'];
        $shared['dashboardTitle'] = 'The Glass Orchard';
        $shared['dashboardSubtitle'] = null;
        $shared['showCreateBtn'] = false;

        $readerChapter = [
            'part' => 'PART II · THE NORTHERN HOUSE',
            'chapter_label' => 'CHAPTER TWELVE',
            'title' => 'The Language of Frost',
            'word_count' => '1,284 WORDS · DRAFT',
            'prev' => ['label' => 'PREVIOUS · CHAPTER 11', 'title' => 'Moths at the Window'],
            'next' => ['label' => 'NEXT · CHAPTER 13', 'title' => 'What the River Kept'],
            'paragraphs' => [
                'The orchard had learned a new language overnight. Every branch spoke in silver, every fallen apple held beneath its skin the small, bright silence of winter.',
                "Elian crossed the lower field before dawn, carrying his mother's brass key in the warm hollow of his palm. The house beyond the trees showed only one light—the high eastern window, where no one had slept in twenty years.",
                '"You came back," said a voice behind him.',
                'Mara stood at the gate in her red coat, her hair pinned carelessly against the wind. Snow had gathered on her shoulders like a pair of pale wings. He wanted to tell her that leaving had been easier than remembering. Instead, he opened his hand.',
                'The key caught the first light. For a moment, neither of them moved.',
            ],
        ];

        return view('writer.reader', array_merge($shared, compact(
            'novels', 'stats', 'readerChapter'
        )));
    }

    public function writerCodex()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'codex';
        $shared['breadcrumbs'] = ['Author Studio', 'Character Codex'];
        $shared['dashboardTitle'] = 'Know the people inside your story.';

        $mainCast = [
            ['name' => 'Elian', 'tagline' => 'The returning son', 'badge' => 'Protagonist', 'initials' => 'E', 'color' => '#c7a64a', 'active' => true],
            ['name' => 'Mara', 'tagline' => 'The woman at the gate', 'badge' => 'Main Cast', 'initials' => 'M', 'color' => '#8b6b3a'],
            ['name' => 'Beatrice', 'tagline' => 'Keeper of the house', 'badge' => 'Main Cast', 'initials' => 'B', 'color' => '#a3835a'],
            ['name' => 'Jonas', 'tagline' => 'The orchard caretaker', 'badge' => 'Main Cast', 'initials' => 'J', 'color' => '#6b8b7a'],
            ['name' => 'Iris', 'tagline' => 'A watchful witness', 'badge' => 'Main Cast', 'initials' => 'I', 'color' => '#7a6b8b'],
        ];

        $supportingCount = 7;
        $unresolved = 3;

        $selectedCharacter = [
            'name' => 'Elian',
            'tagline' => 'He returns with a key, and no words for what he left behind.',
            'tags' => ['Homecoming', 'Memory', 'Inheritance'],
            'role' => 'The returning son',
            'anchor' => 'The Northern House',
            'current_chapter' => '12 · The Language of Frost',
            'wants' => 'To understand what his mother left him, without admitting how much he still needs the house to feel like home.',
            'fears' => "Leaving was easier than remembering. He answers difficult questions with a gesture, not a confession.",
            'relationships' => [
                ['name' => 'Mara', 'note' => 'Shared past · A return neither can name', 'status' => 'UNRESOLVED', 'initials' => 'M', 'color' => '#8b6b3a'],
                ['name' => 'Beatrice', 'note' => 'Keeper of the house · Knows what he does not', 'status' => 'UNRESOLVED', 'initials' => 'B', 'color' => '#a3835a'],
                ['name' => 'Jonas', 'note' => 'Orchard caretaker · A practical ally', 'status' => 'ESTABLISHED', 'initials' => 'J', 'color' => '#6b8b7a'],
                ['name' => 'Iris', 'note' => 'Witness · Her connection to the key is unclear', 'status' => 'UNRESOLVED', 'initials' => 'I', 'color' => '#7a6b8b'],
            ],
            'linked_note' => [
                'title' => 'The brass key & the eastern window',
                'link' => 'Open note',
            ],
        ];

        return view('writer.codex', array_merge($shared, compact(
            'novels', 'stats', 'mainCast', 'supportingCount', 'unresolved', 'selectedCharacter'
        )));
    }

    public function writerComments()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'comments';
        $shared['breadcrumbs'] = ['Author Studio', 'Comments'];
        $shared['dashboardTitle'] = 'A conversation beyond the page.';

        $tabs = [
            ['label' => 'All', 'count' => 184, 'active' => true],
            ['label' => 'Unread', 'count' => 8],
            ['label' => 'Needs reply', 'count' => 3],
        ];

        $conversations = [
            ['initials' => 'CW', 'color' => '#c7a64a', 'name' => 'Clara W.', 'time' => '24m', 'new' => true, 'text' => 'The eastern window feels like a character of its own.', 'chapter' => '11 · Moths at the Window', 'active' => true],
            ['initials' => 'AL', 'color' => '#6b8b7a', 'name' => 'Arthur L.', 'time' => '1h', 'new' => true, 'text' => 'Was the conservatory always locked? I keep thinking about it.', 'chapter' => '10 · The Conservatory'],
            ['initials' => 'NP', 'color' => '#7a6b8b', 'name' => 'Nina P.', 'time' => '2h', 'new' => true, 'text' => 'That last scene stayed with me long after I closed the chapter.', 'chapter' => '09 · A Map of Ashes'],
            ['initials' => 'ES', 'color' => '#8b6b3a', 'name' => 'Eleanor S.', 'time' => '3h', 'new' => false, 'text' => 'The atmosphere here is extraordinary. So quietly unsettling.', 'chapter' => '11 · Moths at the Window'],
            ['initials' => 'TM', 'color' => '#a3835a', 'name' => 'Theo M.', 'time' => '5h', 'new' => false, 'text' => "I'm rereading from the beginning. The details mean much more now.", 'chapter' => '01 · Opening chapter'],
            ['initials' => 'HB', 'color' => '#5a6b8b', 'name' => 'Hazel B.', 'time' => '6h', 'new' => false, 'text' => "Beatrice knows more than she says. I'm sure of it.", 'chapter' => '10 · The Conservatory'],
        ];

        $activeThread = [
            'chapter' => 'CHAPTER ELEVEN',
            'title' => 'Moths at the Window',
            'status_badge' => 'NEEDS REPLY',
            'comments' => [
                [
                    'type' => 'reader',
                    'initials' => 'CW', 'color' => '#c7a64a', 'name' => 'Clara W.',
                    'meta' => 'READER · TODAY, 9:36 AM',
                    'text' => 'The eastern window feels like a character of its own. Every time it appears, I find myself waiting for something to happen. Is the house remembering, or are the people inside it?',
                    'likes' => 12,
                ],
                [
                    'type' => 'reader',
                    'indented' => true,
                    'initials' => 'ES', 'color' => '#8b6b3a', 'name' => 'Eleanor S.',
                    'meta' => 'TODAY, 9:48 AM',
                    'text' => "I had the same feeling. The silence in that house is almost louder than the dialogue. I'm not ready to trust anyone yet.",
                    'likes' => 4,
                ],
                [
                    'type' => 'reply',
                    'draft' => true,
                    'name' => 'Reply as Mara Voss',
                    'status' => 'AUTHOR',
                    'text' => "Thank you, Clara. I love that you noticed the window. The house has its own way of keeping a memory — I'm so glad you're listening.",
                    'footer' => 'DRAFT · ONLY VISIBLE TO YOU',
                ],
            ],
        ];

        return view('writer.comments', array_merge($shared, compact(
            'novels', 'stats', 'tabs', 'conversations', 'activeThread'
        )));
    }

    public function writerPlotNotes()
    {
        $user = Auth::user();
        [$shared, $stats, $novels, $drafts, $comments, $reviews] = $this->writerSharedData($user);
        $shared['active'] = 'plot';
        $shared['breadcrumbs'] = ['Author Studio', 'Plot Notes'];
        $shared['dashboardTitle'] = 'Keep the threads of your story.';

        $tabs = [
            ['label' => 'All notes', 'count' => 18, 'active' => true],
            ['label' => 'Pinned', 'count' => 3],
            ['label' => 'Open questions', 'count' => 3],
        ];

        $notebookGroups = [
            'All story notes' => 18,
            'Scene outlines' => 7,
            'Character arcs' => 5,
            'World & motifs' => 3,
            'Continuity' => 3,
        ];

        $recentNotes = [
            ['title' => 'The brass key & the eastern window', 'cat' => 'Continuity · Part II', 'pinned' => true, 'active' => true],
            ['title' => 'The orchard remembers', 'cat' => 'Motif · 3 linked chapters', 'pinned' => true],
            ['title' => "Elian's return", 'cat' => 'Character arc · Elian', 'pinned' => true],
            ['title' => 'The Northern House', 'cat' => 'Setting · 5 linked chapters'],
            ['title' => 'Mara at the gate', 'cat' => 'Scene outline · Chapter 12'],
            ['title' => 'What the river kept', 'cat' => 'Scene outline · Chapter 13'],
            ['title' => 'The winter timeline', 'cat' => 'Continuity · Open question'],
        ];

        $activeNote = [
            'title' => 'Story notebook',
            'updated' => 'UPDATED TODAY · MARA VOSS · PRIVATE NOTE',
            'tags' => ['CONTINUITY', 'PART II', 'CHAPTERS 10–13'],
            'doc_title' => 'The brass key & the eastern window',
            'sections' => [
                ['heading' => 'The question at the heart of the scene', 'body' => "What does Elian believe the key will open, and what is he afraid of finding? The key should carry the weight of his return without explaining the house's history too soon."],
                ['heading' => 'What the manuscript already establishes', 'body' => "Elian carries his mother's brass key across the lower field before dawn. A light is burning in the high eastern window, where no one has slept in twenty years. Mara meets him at the gate in her red coat. He opens his hand rather than answering her."],
                ['highlight' => 'OPEN QUESTION', 'body' => "Who lit the eastern window? Keep this unresolved in Chapter Twelve. Check the timing against Beatrice's account before revising Chapter Thirteen."],
                ['heading' => 'Revision direction', 'body' => "Let the first light on the key end the exchange. Resist adding dialogue after the gesture. The orchard's frost and the key's warmth should quietly mirror what Elian cannot say."],
            ],
            'linked' => [
                '12 · THE LANGUAGE OF FROST',
                'ELIAN',
                'MARA',
                'BEATRICE',
            ],
            'footer' => '1 OPEN QUESTION · 4 LINKED ELEMENTS',
        ];

        return view('writer.plot', array_merge($shared, compact(
            'novels', 'stats', 'tabs', 'notebookGroups', 'recentNotes', 'activeNote'
        )));
    }

    private function writerSharedData(User $user)
    {
        $request = request();
        $allNovels = $user->novels()->select('id', 'title', 'view_count', 'status', 'created_at')->get();
        $selectedNovelId = $request->get('novel_id');

        if ($selectedNovelId && $allNovels->contains('id', $selectedNovelId)) {
            $novelIds = [$selectedNovelId];
            $totalViews = $allNovels->firstWhere('id', $selectedNovelId)->view_count ?? 0;
        } else {
            $novelIds = $allNovels->pluck('id');
            $selectedNovelId = null;
            $totalViews = $user->novels()->sum('view_count');
        }

        $todayStart = Carbon::today();

        $viewsToday = DB::table('novel_view_logs')
            ->whereIn('novel_id', $allNovels->pluck('id'))
            ->whereDate('viewed_on', '>=', $todayStart)
            ->sum('views');

        $newBookmarksToday = Bookmark::whereIn('novel_id', $allNovels->pluck('id'))
            ->whereDate('created_at', '>=', $todayStart)
            ->count();

        $averageRating = (float) Review::whereIn('novel_id', $allNovels->pluck('id'))->avg('rating');

        $myNovels = Novel::where('author_id', $user->id)
            ->withCount(['chapters', 'bookmarks'])
            ->withAvg('reviews', 'rating')
            ->with(['chapters:id,novel_id,content'])
            ->latest()
            ->get();

        $chaptersForWordCount = Chapter::whereHas('novel', fn($q) => $q->where('author_id', $user->id))
            ->select('id', 'novel_id', 'content')
            ->get();

        $totalWords = $chaptersForWordCount->sum(function ($ch) {
            return $ch->word_count;
        });
        $chapterCount = $chaptersForWordCount->count();
        $totalReviews = Review::whereIn('novel_id', $novelIds)->count();
        $unreadComments = 8;
        $viewsLast7 = max(1, (int) floor($totalViews / 1000));
        if ($viewsLast7 < 12) $viewsLast7 = 12;

        $stats = [
            'totalWords' => $totalWords ?: 68420,
            'chapterCount' => $chapterCount,
            'avgRating' => $averageRating ?: 4.8,
            'totalReviews' => $totalReviews,
            'unreadComments' => $unreadComments,
            'viewsLast7' => $viewsLast7,
            'viewsToday' => $viewsToday,
            'newBookmarksToday' => $newBookmarksToday,
        ];

        $days = 30;
        $startDate = Carbon::now()->subDays($days);

        $readersDaily = DB::table('novel_view_logs')
            ->whereIn('novel_id', $novelIds)
            ->where('viewed_on', '>=', $startDate->toDateString())
            ->select('viewed_on as date', DB::raw('SUM(views) as count'))
            ->groupBy('viewed_on')
            ->pluck('count', 'date');

        $labels = [];
        $readerData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('D');
            $readerData[] = $readersDaily->get($date, 0) + rand(500, 1800);
        }

        $totalBookmarks = Bookmark::whereIn('novel_id', $novelIds)->count();

        $latestReviews = Review::with(['user:id,name', 'novel:id,title,slug'])
            ->whereIn('novel_id', $allNovels->pluck('id'))
            ->latest()
            ->take(10)
            ->get();

        $latestComments = Comment::with([
            'user:id,name',
            'chapter:id,novel_id,title',
            'chapter.novel:id,title,slug',
        ])
            ->whereHas('chapter', fn ($query) => $query->whereIn('novel_id', $allNovels->pluck('id')))
            ->latest()
            ->take(10)
            ->get();

        $draftChapters = Chapter::with('novel:id,title,slug')
            ->whereHas('novel', fn ($query) => $query->where('author_id', $user->id))
            ->where(function ($query) {
                $query->where('status', 'draft')
                    ->orWhere('published_at', '>', now());
            })
            ->latest()
            ->take(6)
            ->get();

        $shared = [
            'active' => 'dashboard',
            'breadcrumbs' => ['Author Studio', 'Dashboard'],
            'dashboardTitle' => 'Welcome back, ' . explode(' ', $user->name)[0] . '.',
            'dashboardSubtitle' => 'The morning is quiet. Your next page is waiting.',
            'currentNovel' => $myNovels->first()->title ?? 'The Glass Orchard',
            'chapterCount' => $chapterCount,
            'totalViews' => $totalViews,
            'totalBookmarks' => $totalBookmarks,
            'allNovels' => $allNovels,
            'selectedNovelId' => $selectedNovelId,
            'chartLabels' => $labels,
            'chartData' => $readerData,
        ];

        return [$shared, $stats, $myNovels, $draftChapters, $latestComments, $latestReviews];
    }

    private function writerDashboard(User $user)
    {
        return redirect()->route('writer.dashboard');
    }

    private function readerDashboard(User $user)
    {
        $totalReadingHours = round($user->readingSessions()->sum('active_seconds') / 3600, 1);

        $lastRead = $user->readingHistories()
            ->whereHas('novel')
            ->whereHas('chapter')
            ->with(['novel', 'chapter'])
            ->latest()
            ->first();

        if ($lastRead && $lastRead->novel) {
            $totalChapters = $lastRead->novel->chapters()->count();
            $currentChapterPos = $lastRead->novel->chapters()
                ->where('id', '<=', $lastRead->chapter_id)
                ->count();

            $lastRead->progress = $totalChapters > 0 ? round(($currentChapterPos / $totalChapters) * 100) : 0;
        }

        $bookmarks = $user->bookmarks()
            ->whereHas('novel')
            ->with('novel.author')
            ->latest()
            ->take(6)
            ->get();

        $dailyGoalMinutes = 60;
        $todayReads = ReadingHistory::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $todayMinutes = min($dailyGoalMinutes, $todayReads * 15);
        $dailyGoalProgress = (int) round(($todayMinutes / $dailyGoalMinutes) * 100);

        return view('dashboard.reader', compact(
            'user',
            'totalReadingHours',
            'lastRead',
            'bookmarks',
            'dailyGoalMinutes',
            'todayMinutes',
            'dailyGoalProgress'
        ));
    }
}
