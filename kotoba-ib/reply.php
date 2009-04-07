<?php
/*************************************
 * Этот файл является частью Kotoba. *
 * Файл license.txt содержит условия *
 * распространения Kotoba.           *
 *************************************/
/*********************************
 * This file is part of Kotoba.  *
 * See license.txt for more info.*
 *********************************/

require 'common.php';

$HEAD = 
'<html>
<head>
	<title>Error page</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="' . KOTOBA_DIR_PATH . '/kotoba.css">
</head>
<body>
';

$FOOTER = 
'
</body>
</html>';

if(KOTOBA_ENABLE_STAT === true)
    if(($stat_file = @fopen($_SERVER['DOCUMENT_ROOT'] . KOTOBA_DIR_PATH . '/reply.stat', 'a')) === false)
        die($HEAD . '<span class="error">Ошибка. Не удалось открыть или создать файл статистики.</span>' . $FOOTER);

require 'events.php';

if(!isset($_POST['b']))
{
	if(KOTOBA_ENABLE_STAT)
		kotoba_stat(ERR_BOARD_NOT_SPECIFED);

	die($HEAD . '<span class="error">Ошибка. Не задано имя доски.</span>' . $FOOTER);
}

if(!isset($_POST['t']))
{
	if(KOTOBA_ENABLE_STAT)
		kotoba_stat(ERR_THREAD_NOT_SPECIFED);

	die($HEAD . '<span class="error">Ошибка. Не задан номер треда.</span>' . $FOOTER);
}

if(($BOARD_NAME = CheckFormat('board', $_POST['b'])) === false)
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_BOARD_BAD_FORMAT);
        
    die($HEAD . '<span class="error">Ошибка. Имя доски имеет не верный формат.</span>' . $FOOTER);
}

if(($THREAD_NUM = CheckFormat('thread', $_POST['t'])) === false)
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_THREAD_BAD_FORMAT);
        
    die($HEAD . '<span class="error">Ошибка. Номер треда имеет не верный формат.</span>' . $FOOTER);
}

require 'databaseconnect.php';
$BOARD_NUM = -1;

// Проверка существования доски с именем $BOARD_NAME.
if(($result = mysql_query("select `id` from `boards` where `Name` = \"$BOARD_NAME\"")) !== false)
{
	if(mysql_num_rows($result) != 1)
	{
        if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_BOARD_NOT_FOUND, $BOARD_NAME));
			
		mysql_free_result($result);
		die($HEAD . "<span class=\"error\">Ошибка. Доски с именем $BOARD_NAME не существует.</span>" . $FOOTER);
	}
	else
	{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$BOARD_NUM = $row['id'];
        mysql_free_result($result);
    }
}
else
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(sprintf(ERR_BOARD_EXIST_CHECK, $BOARD_NAME, mysql_error()));
        
    die($HEAD . "<span class=\"error\">Ошибка. Не удалось проверить существание доски с именем $BOARD_NAME. Прична: " .  mysql_error() . '</span>' . $FOOTER);
}

// Проверка существования треда $THREAD_NUM на доске с именем $BOARD_NAME.
if(($result = mysql_query("select t.`id`, count(p.`id`) `count`
	from `threads` t join `posts` p on t.`id` = p.`thread` and t.`board` = p.`board`
	where t.`id` = $THREAD_NUM and t.`board` = $BOARD_NUM group by t.`id`")) !== false)
{
	if(mysql_num_rows($result) != 1)
	{
        if(KOTOBA_ENABLE_STAT)
			kotoba_stat(sprintf(ERR_THREAD_NOT_FOUND, $THREAD_NUM, $BOARD_NAME));
			
		mysql_free_result($result);
		die($HEAD . "<span class=\"error\">Ошибка. Треда с номером $THREAD_NUM на доске $BOARD_NAME не найдено.</span>" . $FOOTER);
	}
	else
	{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$THREAD_POSTCOUNT = $row['count'];
        mysql_free_result($result);
    }
}
else
{
	if(KOTOBA_ENABLE_STAT)
        kotoba_stat(sprintf(ERR_THREAD_EXIST_CHECK, $THREAD_NUM, $BOARD_NAME, mysql_error()));
    
	die($HEAD . "<span class=\"error\">Ошибка. Не удалось проверить существание треда с номером $THREAD_NUM на доске $BOARD_NAME. Прична: " .  mysql_error() . "</error>" . $FOOTER);
}

switch($_FILES['Message_img']['error'])
{
    case UPLOAD_ERR_INI_SIZE:
		if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_UPLOAD_INI_SIZE);

		die($HEAD . '<span class="error">Ошибка. Загруженный файл превышает размер, заданный директивой upload_max_filesize в php.ini.</span>' . $FOOTER);
    break;

    case UPLOAD_ERR_FORM_SIZE:
		if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_UPLOAD_FORM_SIZE);

		die($HEAD . '<span class="error">Ошибка. Загруженный файл превышает размер, заданный директивой MAX_FILE_SIZE, определённой в HTML форме.</span>' . $FOOTER);
    break;
    
    case UPLOAD_ERR_PARTIAL:
		if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_UPLOAD_PARTIAL);

		die($HEAD . '<span class="error">Ошибка. Файл был загружен лишь частично.</span>' . $FOOTER);
    break;
    
    case UPLOAD_ERR_NO_TMP_DIR:
		if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_UPLOAD_NO_TMP_DIR);

		die($HEAD . '<span class="error">Ошибка. Временная папка не найдена.</span>' . $FOOTER);
    break;
    
    case UPLOAD_ERR_CANT_WRITE:
		if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_UPLOAD_CANT_WRITE);

		die($HEAD . '<span class="error">Ошибка. Не удалось записать файл на диск.</span>' . $FOOTER);
    break;
    
    case UPLOAD_ERR_EXTENSION:
		if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_UPLOAD_EXTENSION);

		die($HEAD . '<span class="error">Ошибка. Загрузка файла прервана расширением.</span>' . $FOOTER);
    break;
}

if($_FILES['Message_img']['error'] == UPLOAD_ERR_NO_FILE && (!isset($_POST['Message_text']) || $_POST['Message_text'] == ''))
{
	if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_NO_FILE_AND_TEXT);
		
    die($HEAD . '<span class="error">Ошибка. Файл не был загружен и пустой текст сообщения.</error>' . $FOOTER);
}

if(strlen($_POST['Message_text']) > KOTOBA_MAX_MESSAGE_LENGTH)
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_TEXT_TOO_LONG);
        
    die ($HEAD . '<span class="error">Ошибка. Текст сообщения слишком длинный.</span>' . $FOOTER);
}

if(strlen($_POST['Message_theme']) > KOTOBA_MAX_THEME_LENGTH)
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_THEME_TOO_LONG);

    die ($HEAD . '<span class="error">Ошибка. Тема слишком длинная.</span>' . $FOOTER);
}

if(strlen($_POST['Message_name']) > KOTOBA_MAX_NAME_LENGTH)
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_NAME_TOO_LONG);

    die ($HEAD . '<span class="error">Ошибка. Имя пользователя слишком длинное.</span>' . $FOOTER);
}

$Message_text = htmlspecialchars($_POST['Message_text'], ENT_QUOTES);
$Message_theme = htmlspecialchars($_POST['Message_theme'], ENT_QUOTES);
$Message_name = htmlspecialchars($_POST['Message_name'], ENT_QUOTES);

if(strlen($Message_text) > KOTOBA_MAX_MESSAGE_LENGTH)
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_TEXT_TOO_LONG);
        
    die ($HEAD . '<span class="error">Ошибка. Текст сообщения слишком длинный.</span>' . $FOOTER);
}

if(strlen($$Message_theme) > KOTOBA_MAX_THEME_LENGTH)
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_THEME_TOO_LONG);

    die ($HEAD . '<span class="error">Ошибка. Тема слишком длинная.</span>' . $FOOTER);
}

if(strlen($Message_name) > KOTOBA_MAX_NAME_LENGTH)
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_NAME_TOO_LONG);

    die ($HEAD . '<span class="error">Ошибка. Имя пользователя слишком длинное.</span>' . $FOOTER);
}

require 'mark.php';

KotobaMark($Message_text);
$Message_text = preg_replace("/\n/", '<br>', $Message_text);

if(strlen($Message_text) > KOTOBA_MAX_MESSAGE_LENGTH)
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(ERR_TEXT_TOO_LONG);
        
    die ($HEAD . '<span class="error">Ошибка. Текст сообщения слишком длинный.</span>' . $FOOTER);
}

$Message_theme = str_replace("\n", '', $Message_theme);
$Message_theme = str_replace("\r", '', $Message_theme);

$Message_name = str_replace("\n", '', $Message_name);
$Message_name = str_replace("\r", '', $Message_name);

$Message_text = preg_replace('/(<br>){3,}/', '<br><br>', $Message_text);
$with_image = false;

if($_FILES['Message_img']['error'] == UPLOAD_ERR_OK)
{
    if($_FILES['Message_img']['size'] < KOTOBA_MIN_IMGSIZE)
    {
        if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_FILE_TOO_SMALL);

		die($HEAD . '<span class="error">Ошибка. Загружаемый файл имеет слишком маленький размер.</span>' . $FOOTER);
    }

	if(($dot_pos = strrpos($_FILES['Message_img']['name'], '.')) === false)
	{
		if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_WRONG_FILETYPE);

		die ($HEAD . '<span class="error">Ошибка. Недопустимый тип файла.</span>' . $FOOTER);
    }

    $recived_ext = strtolower(substr($_FILES['Message_img']['name'], $dot_pos + 1));

    switch($recived_ext)
    {
        case 'jpeg':
            $recived_ext = 'jpg';
            break;
        case 'gif':
        case 'png':
        case 'jpg':
            break;
        default:
            if(KOTOBA_ENABLE_STAT)
				kotoba_stat(ERR_WRONG_FILETYPE);

			die ($HEAD . '<span class="error">Ошибка. Недопустимый тип файла.</span>' . $FOOTER);
    }

    list($usec, $sec) = explode(" ", microtime());
    $saved_filename = $sec . substr($usec, 2, 5);
    $saved_thumbname = $saved_filename . "t." . $recived_ext;
    $raw_filename = $saved_filename;
    $saved_filename .= ".$recived_ext";

    $IMG_SRC_DIR = $_SERVER['DOCUMENT_ROOT'] . KOTOBA_DIR_PATH . "/$BOARD_NAME/img";
    $IMG_THU_DIR = $_SERVER['DOCUMENT_ROOT'] . KOTOBA_DIR_PATH . "/$BOARD_NAME/thumb";

    if (move_uploaded_file($_FILES['Message_img']['tmp_name'], "$IMG_SRC_DIR/$saved_filename") === false)
    {
        if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_FILE_NOT_SAVED);

		die ($HEAD . '<span class="error">Ошибка. Файл не удалось сохранить.</span>' . $FOOTER);
    }
    
    if(KOTOBA_ALLOW_SAEMIMG)
    {
        if(($img_hash = hash_file('md5', "$IMG_SRC_DIR/$saved_filename")) === false)
        {
            if(KOTOBA_ENABLE_STAT)
                kotoba_stat(ERR_FILE_HASH);

            die ($HEAD . "<span class=\"error\">Ошибка. Не удалось вычислить хеш файла $IMG_SRC_DIR/$saved_filename.</span>" . $FOOTER);
        }

        if(($result = mysql_query("select `id`, `thread` from `posts` where `board` = $BOARD_NUM and LOCATE(\"HASH:$img_hash\",`Post Settings`) <> 0")) !== false)
        {
            if(mysql_num_rows($result) == 0)
            {
                mysql_free_result($result);
            }
            else
            {
                if(KOTOBA_ENABLE_STAT)
					kotoba_stat(ERR_FILE_ALREADY_EXIST);

				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				mysql_free_result($result);
				unlink("$IMG_SRC_DIR/$saved_filename");
				die($HEAD . "<span class=\"error\">Ошибка. Картинка уже была запощена <a href=\"" . KOTOBA_DIR_PATH . "/$BOARD_NAME/$row[thread]/$row[id]/\">тут</a></span>" . $FOOTER);

            }
        }
        else
        {
            kotoba_stat("(0026) Ошибка. Не удалось проверить существание картинки на доске с именем $BOARD_NAME. Прична: " . mysql_error());
            die($HEAD . "<span class=\"error\">Ошибка. Не удалось проверить существание картинки на доске с именем $BOARD_NAME. Прична: " .  mysql_error() . "</error>" . $FOOTER);
        }
    }

    $srcimg_res = getimagesize("$IMG_SRC_DIR/$saved_filename");

    if($srcimg_res[0] < KOTOBA_MIN_IMGWIDTH && $srcimg_res[1] < KOTOBA_MIN_IMGHEIGTH)
    {
        if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_FILE_LOW_RESOLUTION);

		unlink("$IMG_SRC_DIR/$saved_filename");
		die($HEAD . '<span class="error">Ошибка. Разрешение загружаемого изображения слишком маленькое.</span>' . $FOOTER);
    }

	// TODO Сделать проверку на слишком большое расширение.

    require 'thumb_processing.php';

	$thumb_res = createThumbnail("$IMG_SRC_DIR/$saved_filename", "$IMG_THU_DIR/$saved_thumbname", $recived_ext, $srcimg_res[0], $srcimg_res[1], 200, 200);

	if($thumb_res != KOTOBA_THUMB_SUCCESS)
	{
		// TODO Сделать вывод причины неудачи создания тумбочки в лог.
		if(KOTOBA_ENABLE_STAT)
			kotoba_stat(ERR_THUMB_CREATION);

		unlink("$IMG_SRC_DIR/$saved_filename");

		switch($thumb_res)
		{
			case KOTOBA_THUMB_UNSUPPORTED:	// unsupported format
				$message = "usupported file format";
				break;
			case KOTOBA_THUMB_NOLIBRARY:	// no suitable library
				$message = "no suitable library for image processing";
				break;
			case KOTOBA_THUMB_TOOBIG	:	// file too big
				$message = "image file too big";
				break;
			case KOTOBA_THUMB_UNKNOWN:	// unknown error
				$message = "unknown error";
				break;
			default:
				$message = "...";
				break;
		}

		die ($HEAD . '<span class="error">Ошибка. Не удалось создать уменьшенную копию изображения:' . $message .'</span>' .  $FOOTER);
	}

	$thumb_res = getimagesize("$IMG_THU_DIR/$saved_thumbname");

    $Message_img_params = "IMGNAME:$raw_filename\n";
    $Message_img_params .= "IMGEXT:$recived_ext\n";
    $Message_img_params .= "IMGTW:$thumb_res[0]\n";
    $Message_img_params .= "IMGTH:$thumb_res[1]\n";
    $Message_img_params .= "IMGSW:$srcimg_res[0]\n";
    $Message_img_params .= "IMGSH:$srcimg_res[1]\n";
    $Message_img_params .= "IMGSIZE:{$_FILES['Message_img']['size']}\n";
    
    if(KOTOBA_ALLOW_SAEMIMG)
        $Message_img_params .= "HASH:$img_hash\n";

    $with_image = true;
}

$Message_settings = "THEME:$Message_theme\n";
$Message_settings .= "NAME:$Message_name\n";
$Message_settings .= "IP:$_SERVER[REMOTE_ADDR]\n";

if(isset($_POST['Sage']) && $_POST['Sage'] == 'sage')
    $Message_settings .= "SAGE:Y\n";

if($THREAD_POSTCOUNT > KOTOBA_BUMPLIMIT)
	$Message_settings .= "BLIMIT:Y\n";

if($with_image)
    $Message_settings .= $Message_img_params;

if(isset($_POST['Message_pass']) && $_POST['Message_pass'] != '')
{
	if(($REPLY_PASS = CheckFormat('pass', $_POST['Message_pass'])) === false)
	{
		if(KOTOBA_ENABLE_STAT)
            kotoba_stat(ERR_PASS_BAD_FORMAT);
        
        unlink("$IMG_SRC_DIR/$saved_filename");
        unlink("$IMG_THU_DIR/$saved_thumbname");
        die($HEAD . '<span class="error">Ошибка. Пароль для удаления имеет не верный формат.</span>' . $FOOTER);
	}

	if(!isset($_COOKIE['rempass']) || $_COOKIE['rempass'] != $REPLY_PASS)
		setcookie("rempass", $REPLY_PASS);
		
	$Message_settings .= "REMPASS:$REPLY_PASS\n";
}

if(mysql_query('start transaction') == false)
{
	if(KOTOBA_ENABLE_STAT)
        kotoba_stat(sprintf(ERR_TRAN_FAILED, mysql_error()));
		
    if($with_image)
    {
        unlink("$IMG_SRC_DIR/$saved_filename");
        unlink("$IMG_THU_DIR/$saved_thumbname");
    }

    die ($HEAD . '<span class="error">Ошибка. Невозможно начать транзакцию. Причина: ' . mysql_error() . '.</span>' . $FOOTER);
}

// Вычисление числа постов доски (в не утонувших тредах).
if(($result = mysql_query(
	"select count(p.`id`) `count`
	from `posts` p
	join `threads` t on p.`thread` = t.`id` and p.`board` = t.`board`
	where p.`board` = $BOARD_NUM and (position('ARCHIVE:YES' in t.`Thread Settings`) = 0 or t.`Thread Settings` is null)
	group by p.`board`")) == false || mysql_num_rows($result) == 0)
{
	$temp = mysql_error();
	mysql_query('rollback');
	
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(sprintf(ERR_POST_COUNT_CALC, $BOARD_NAME, $temp));

	unlink("$IMG_SRC_DIR/$saved_filename");
    unlink("$IMG_THU_DIR/$saved_thumbname");
    die ($HEAD . "<span class=\"error\">Ошибка. Невозможно подсчитать количество постов доски $BOARD_NAME. Причина: $temp.</span>" . $FOOTER);
}
elseif (mysql_num_rows($result) == 0)   // Нельзя ответить в тред которого нет, если доска пуста.
{
    if(KOTOBA_ENABLE_STAT)
        kotoba_stat(sprintf(ERR_POST_COUNT_CALC, $BOARD_NAME, 'Возможно не верное имя доски'));

	mysql_query('rollback');
    unlink("$IMG_SRC_DIR/$saved_filename");
    unlink("$IMG_THU_DIR/$saved_thumbname");
    die ($HEAD . "<span class=\"error\">Ошибка. Невозможно подсчитать количество постов доски $BOARD_NAME. Причина: Возможно не верное имя доски.</span>" . $FOOTER);
}

$row = mysql_fetch_array($result, MYSQL_ASSOC);
$POST_COUNT = $row['count'];
mysql_free_result($result);

// Топим треды.
// TODO Косяк с тредами, в которых постов больше чем лимит постов на доске.
while($POST_COUNT >= KOTOBA_POST_LIMIT)
{
	// Выберем тред, ответ в который был наиболее ранним, и количество постов в нем.
	if(($result = mysql_query(
        "select p.`thread`, count(p.`id`) `count`
        from `posts` p 
        join `threads` t on p.`thread` = t.`id` and p.`board` = t.`board` 
        where t.`board` = $BOARD_NUM and (position('ARCHIVE:YES' in t.`Thread Settings`) = 0 or t.`Thread Settings` is null) and (position('SAGE:Y' in p.`Post Settings`) = 0 or p.`Post Settings` is null) 
        group by p.`thread` 
        order by max(p.`id`) asc limit 1")) == false)
    {
        $temp = mysql_error();
        mysql_query('rollback');
        
        if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_ARCH_THREAD_SEARCH, $temp));
        
		if($with_image === true)
		{
			unlink("$IMG_SRC_DIR/$saved_filename");
			unlink("$IMG_THU_DIR/$saved_thumbname");
		}

		die ($HEAD . "<span class=\"error\">Ошибка. Невозможно найти тред для сброса в архив. Причина: $temp.</span>" . $FOOTER);
    }
    elseif (mysql_num_rows($result) == 0)
    {
        if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_ARCH_THREAD_SEARCH, "Возможно не верный номер доски $BOARD_NUM"));
        
		if($with_image === true)
		{
			unlink("$IMG_SRC_DIR/$saved_filename");
			unlink("$IMG_THU_DIR/$saved_thumbname");
		}

        mysql_query('rollback');
        die ($HEAD . "<span class=\"error\">Ошибка. Невозможно найти тред для сброса в архив. Причина: Возможно не верный номер доски $BOARD_NUM.</span>" . $FOOTER);
    }

    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $ARCH_THREAD_NUM = $row['thread'];
    $ARCH_THREAD_POSTCOUNT = $row['count'];
    mysql_free_result($result);
    $Thread_Settings = "ARCHIVE:YES\n";

    if(mysql_query("update `threads` set `Thread Settings` = case when `Thread Settings` is null then concat('', '$Thread_Settings') else concat(`Thread Settings`, '$Thread_Settings') end where `id` = $ARCH_THREAD_NUM and `board` = $BOARD_NUM") === false)
    {
        $temp = mysql_error();
        mysql_query('rollback');
        
        if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_ARCH_THREAD_MARK, $temp));
        
		if($with_image === true)
		{
			unlink("$IMG_SRC_DIR/$saved_filename");
			unlink("$IMG_THU_DIR/$saved_thumbname");
		}

		die ($HEAD . "<span class=\"error\">Ошибка. Невозможно пометить тред для архивирования. Причина: $temp.</span>" . $FOOTER);
    }elseif (mysql_affected_rows() == 0)
    {
        if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_ARCH_THREAD_MARK, "Возможно не верный номер доски $BOARD_NUM или треда для архивирования $ARCH_THREAD_NUM"));
        
		if($with_image === true)
		{
			unlink("$IMG_SRC_DIR/$saved_filename");
			unlink("$IMG_THU_DIR/$saved_thumbname");
		}

        mysql_query('rollback');
        die ($HEAD . "<span class=\"error\">Ошибка. Невозможно пометить тред на архивирование. Причина: Возможно не верный номер доски $BOARD_NUM или треда для архивирования $ARCH_THREAD_NUM.</span>" . $FOOTER);
    }
    
    if(($result = mysql_query(
        'select count(p.`id`) `count` ' .
        'from `posts` p ' .
        'join `threads` t on p.`thread` = t.`id` and p.`board` = t.`board` ' .
        'where p.`board` = ' . $BOARD_NUM . ' and (position(\'ARCHIVE:YES\' in t.`Thread Settings`) = 0 or t.`Thread Settings` is null) ' .
        'group by p.`board`')) == false)
    {
        $temp = mysql_error();
        mysql_query('rollback');
        
        if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_POST_COUNT_CALC, $BOARD_NAME, $temp));

        if($with_image === true)
		{
			unlink("$IMG_SRC_DIR/$saved_filename");
			unlink("$IMG_THU_DIR/$saved_thumbname");
		}

		die ($HEAD . "<span class=\"error\">Ошибка. Невозможно подсчитать  количество постов доски $BOARD_NAME. Причина: $temp.</span>" . $FOOTER);
    }
    elseif (mysql_num_rows($result) == 0)
    {
        if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_POST_COUNT_CALC, $BOARD_NAME, 'Возможно не верное имя доски'));
			
		if($with_image === true)
		{
			unlink("$IMG_SRC_DIR/$saved_filename");
			unlink("$IMG_THU_DIR/$saved_thumbname");
		}

        mysql_query('rollback');
        die ($HEAD . "<span class=\"error\">Ошибка. Невозможно подсчитать количество постов доски $BOARD_NAME. Причина: Возможно не верное имя доски.</span>" . $FOOTER);
    }

    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $POST_COUNT = $row['count'];
    mysql_free_result($result);
	
    if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(INFO_THREAD_ARCHIVED, $ARCH_THREAD_NUM, $ARCH_THREAD_POSTCOUNT, $BOARD_NUM, $POST_COUNT), false);
}

// `MaxPostNum` не может быть NULL.
if(mysql_query("select @post_num := `MaxPostNum` + 1 from `boards` where `id` = $BOARD_NUM") == false)
{
	$temp = mysql_error();
    mysql_query('rollback');
        
    if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_NEW_POSTNUM_CALC, $temp));
	
    if($with_image === true)
    {
        unlink("$IMG_SRC_DIR/$saved_filename");
        unlink("$IMG_THU_DIR/$saved_thumbname");
    }

    die ($HEAD . "<span class=\"error\"> Ошибка. Невозможно вычислить номер нового поста. Причина: $temp.</span>" . $FOOTER);
}

if(mysql_query(
	"insert into `posts` (`id`, `thread`, `board`, `Time`, `Text`, `Post Settings`)
	values (@post_num, $THREAD_NUM, $BOARD_NUM, '" . date("Y-m-d H:i:s") . "', '$Message_text', '$Message_settings')") == false)
{
    $temp = mysql_error();
    mysql_query('rollback');
    
    if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_NEW_POST_CREATE, $temp));

    if($with_image === true)
    {
        unlink("$IMG_SRC_DIR/$saved_filename");
        unlink("$IMG_THU_DIR/$saved_thumbname");
    }

    die ($HEAD . "<span class=\"error\">Ошибка. Не удалось сохранить пост. Причина: $temp.</span>" . $FOOTER);
}

if(mysql_query("update `boards` set `MaxPostNum` = `MaxPostNum` + 1 where `id` = $BOARD_NUM") == false)
{
    $temp = mysql_error();
    mysql_query('rollback');

    if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_SET_MAXPOST, $temp));
    
    if($with_image === true)
    {
		unlink("$IMG_SRC_DIR/$saved_filename");
		unlink("$IMG_THU_DIR/$saved_thumbname");
	}
	
    die ($HEAD . "<span class=\"error\">Ошибка. Невозможно установить наибольший номер поста доски. Причина: $temp.</span>" . $FOOTER);
}
elseif (mysql_affected_rows() == 0)
{
    if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_SET_MAXPOST, "Возможно не верный номер доски: $BOARD_NUM"));

	if($with_image === true)
    {
		unlink("$IMG_SRC_DIR/$saved_filename");
		unlink("$IMG_THU_DIR/$saved_thumbname");
	}

    mysql_query('rollback');
    die ($HEAD . "<span class=\"error\">Ошибка. Невозможно установить наибольший номер поста доски. Причина: $temp.</span>" . $FOOTER);
}

if(mysql_query('commit') == false)
{
    $temp = mysql_error();
    mysql_query('rollback');
    
    if(KOTOBA_ENABLE_STAT)
            kotoba_stat(sprintf(ERR_TRAN_COMMIT_FAILED,  $temp));

	if($with_image === true)
    {
        unlink("$IMG_SRC_DIR/$saved_filename");
        unlink("$IMG_THU_DIR/$saved_thumbname");
    }
    
	die ($HEAD . "<span class=\"error\">Ошибка. Невозможно завершить транзакцию. Причина: $temp.</span>" . $FOOTER);
}

if(isset($_POST['goto']) && $_POST['goto'] == 'b')
{
    header('Location: ' . KOTOBA_DIR_PATH . "/$BOARD_NAME/");
    exit;
}

header('Location: ' . KOTOBA_DIR_PATH . "/$BOARD_NAME/$THREAD_NUM/");
exit;
?>
<?php
/*
 * Выводит сообщение $errmsg в файл статистики $stat_file.
 */
function kotoba_stat($errmsg, $close_file = true)
{
    global $stat_file;
    fwrite($stat_file, "$errmsg (" . date("Y-m-d H:i:s") . ")\n");

	if($close_file)
		fclose($stat_file);
}
?>