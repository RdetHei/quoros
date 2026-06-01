<?php

namespace App\Enums;

enum NotificationType: string
{
    case ChapterNew = 'chapter_new';
    case CommentReply = 'comment_reply';
    case AuthorChapterNew = 'author_chapter_new';
    case RequestFulfilled = 'request_fulfilled';
    case RequestRejected = 'request_rejected';
}
