<?php
/* *******************************
 * This file is part of Kotoba.  *
 * See license.txt for more info.*
 *********************************/

/**
 * Messages in english.
 * @package api
 */

/**
 * 
 */
require_once '../config.php';

if (!isset($KOTOBA_LOCALE_MESSAGES)) {
    $KOTOBA_LOCALE_MESSAGES = array();
}
$_ = &$KOTOBA_LOCALE_MESSAGES;

$_['ACL.']['eng'] = 'ACL.';
$_['Admin.']['eng'] = 'Admin.';
$_['Annotation too long.']['eng'] = 'Annotation too long.';
$_['Bans.']['eng'] = 'Bans.';
$_['Board id=%d not found.']['eng'] = 'Board id=%d not found.';
$_['Board name wrong format. Board name must be string length at 1 to 16 symbols. Symbols can be latin letters and digits.']['eng'] = 'Board name wrong format. Board name must be string length at 1 to 16 symbols. Symbols can be latin letters and digits.';
$_['Board name=%s not found.']['eng'] = 'Board name=%s not found.';
$_['Board title too long.']['eng'] = 'Board title too long.';
$_['Board, Thread or Post is unique. Set one of it.']['eng'] = 'Board, Thread or Post is unique. Set one of it.';
$_['Boards.']['eng'] = 'Boards.';
$_['Cannot convert image to PNG format.']['eng'] = 'Cannot convert image to PNG format.';
$_['Cant move file %s to %s.']['eng'] = 'Cant move file %s to %s.';
$_['Cant write file to disk.']['eng'] = 'Cant write file to disk.';
$_['Captcha.']['eng'] = 'Captcha.';
$_['Change permission cannot be set without view. Moderate permission cannot be set without all others.']['eng'] = 'Change permission cannot be set without view. Moderate permission cannot be set without all others.';
$_['Copy file.']['eng'] = 'Copy file.';
$_['Failed to copy file %s to %s.']['eng'] = 'Failed to copy file %s to %s.';
$_['Failed to create hard link %s for file %s.']['eng'] = 'Failed to create hard link %s for file %s.';
$_['Failed to open or create log file %s.']['eng'] = 'Failed to open or create log file %s.';
$_['Failed to start session.']['eng'] = 'Failed to start session.';
$_['File %s hash calculation failed.']['eng'] = 'File %s hash calculation failed.';
$_['File is loaded partially.']['eng'] = 'File is loaded partially.';
$_['File type %s not supported for upload.']['eng'] = 'File type %s not supported for upload.';
$_['File uploading interrupted by extension.']['eng'] = 'File uploading interrupted by extension.';
$_['GD doesn\'t support %s file type.']['eng'] = 'GD doesn\'t support %s file type.';
$_['GD library.']['eng'] = 'GD library.';
$_['Groups.']['eng'] = 'Groups.';
$_['Guest.']['eng'] = 'Guest.';
$_['Guests cannot do that.']['eng'] = 'Guests cannot do that.';
$_['Id of new group was not received.']['eng'] = 'Id of new group was not received.';
$_['Image convertion.']['eng'] = 'Image convertion.';
$_['Image dimensions too small.']['eng'] = 'Image dimensions too small.';
$_['Image libraries.']['eng'] = 'Image libraries.';
$_['Image libraries disabled or doesn\'t work.']['eng'] = 'Image libraries disabled or doesn\'t work.';
$_['Image too small.']['eng'] = 'Image too small.';
$_['Imagemagic doesn\'t support %s file type.']['eng'] = 'Imagemagic doesn\'t support %s file type.';
$_['Imagemagic library.']['eng'] = 'Imagemagic library.';
$_['Invlid unicode characters deteced.']['eng'] = 'Invlid unicode characters deteced.';
$_['Language id=%d not exist.']['eng'] = 'Language id=%d not exist.';
$_['Languages.']['eng'] = 'Languages.';
$_['Link creation.']['eng'] = 'Link creation.';
$_['Link too long.']['eng'] = 'Link too long.';
$_['Locale.']['eng'] = 'Locale.';
$_['Logging.']['eng'] = 'Logging.';
$_['Message detected as spam.']['eng'] = 'Message detected as spam.';
$_['Moderator.']['eng'] = 'Moderator.';
$_['Name length too long.']['eng'] = 'Name length too long.';
$_['No attachment and text is empty.']['eng'] = 'No attachment and text is empty.';
$_['No file uploaded.']['eng'] = 'No file uploaded.';
$_['No one group exists.']['eng'] = 'No one group exists.';
$_['No one language exists.']['eng'] = 'No one language exists.';
$_['No one rule in ACL.']['eng'] = 'No one rule in ACL.';
$_['No one stylesheet exists.']['eng'] = 'No one stylesheet exists.';
$_['No one user exists.']['eng'] = 'No one user exists.';
$_['No threads to edit.']['eng'] = 'No threads to edit.';
$_['No words for search.']['eng'] = 'No words for search.';
$_['One of search words is more than 60 characters.']['eng'] = 'One of search words is more than 60 characters.';
$_['Page number=%d not exist.']['eng'] = 'Page number=%d not exist.';
$_['Pages.']['eng'] = 'Pages.';
$_['Post id=%d not found or user id=%d have no permission.']['eng'] = 'Post id=%d not found or user id=%d have no permission.';
$_['Posts.']['eng'] = 'Posts.';
$_['Request method.']['eng'] = 'Request method.';
$_['Request method not defined or unexpected.']['eng'] = 'Request method not defined or unexpected.';
$_['Remote address is not an IP address.']['eng'] = 'Remote address is not an IP address.';
$_['Search.']['eng'] = 'Search.';
$_['Search keyword not set or too short.']['eng'] = 'Search keyword not set or too short.';
$_['Session.']['eng'] = 'Session.';
$_['Setup locale failed.']['eng'] = 'Setup locale failed.';
$_['So small image cannot have so many data.']['eng'] = 'So small image cannot have so many data.';
$_['Spam.']['eng'] = 'Spam.';
$_['Stylesheet id=%d not exist.']['eng'] = 'Stylesheet id=%d not exist.';
$_['Stylesheets.']['eng'] = 'Stylesheets.';
$_['Subject too long.']['eng'] = 'Subject too long.';
$_['Temporary directory not found.']['eng'] = 'Temporary directory not found.';
$_['Text too long.']['eng'] = 'Text too long.';
$_['Thread id=%d not found.']['eng'] = 'Thread id=%d not found.';
$_['Thread number=%d not found.']['eng'] = 'Thread number=%d not found.';
$_['Thread id=%d was archived.']['eng'] = 'Thread id=%d was archived.';
$_['Thread id=%d was closed.']['eng'] = 'Thread id=%d was closed.';
$_['Threads.']['eng'] = 'Threads.';
$_['Unicode.']['eng'] = 'Unicode.';
$_['Unknown upload type.']['eng'] = 'Unknown upload type.';
$_['Upload limit MAX_FILE_SIZE from html form exceeded.']['eng'] = 'Upload limit MAX_FILE_SIZE from html form exceeded.';
$_['Upload limit upload_max_filesize from php.ini exceeded.']['eng'] = 'Upload limit upload_max_filesize from php.ini exceeded.';
$_['Uploads.']['eng'] = 'Uploads.';
$_['User id=%d has no group.']['eng'] = 'User id=%d has no group.';
$_['User keyword=%s not exists.']['eng'] = 'User keyword=%s not exists.';
$_['Users.']['eng'] = 'Users.';
$_['Word too long.']['eng'] = 'Word too long.';
$_['Wordfilter.']['eng'] = 'Wordfilter.';
$_['You are not admin.']['eng'] = 'You are not admin.';
$_['You are not moderator.']['eng'] = 'You are not moderator.';
$_['You enter wrong verification code %s.']['eng'] = 'You enter wrong verification code %s.';
$_['You id=%d have no permission to do it on board id=%d.']['eng'] = 'You id=%d have no permission to do it on board id=%d.';
$_['You id=%d have no permission to do it on thread id=%d.']['eng'] = 'You id=%d have no permission to do it on thread id=%d.';

unset($_);
?>
