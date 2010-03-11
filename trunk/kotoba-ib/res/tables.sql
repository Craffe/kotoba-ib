delimiter |

create table bans					-- Блокировки.
(
	id int not null auto_increment,	-- Идентификатор.
	range_beg bigint not null,		-- Начало диапазона IP-адресов.
	range_end bigint not null,		-- Конец диапазона IP-адресов.
	reason text default null,		-- Причина блокировки.
	untill datetime not null,		-- Время истечения блокировки.
	primary key (id),
	unique key (range_beg, range_end)
)
engine=InnoDB|

create table categories				-- Категории.
(
	id int not null auto_increment,	-- Идентификатор.
	name varchar(50) not null,		-- Имя.
	primary key (id)
)
engine=InnoDB|

-- Заметки:
-- Вложенные файлы - файлы не являющиеся изображениями, загруженные
-- пользователями и сохранённые на сервере. thumbnail - имя файла изображения,
-- который ассоциирован с конкретным типом вложенного файла и служит для него
-- уменьшенной копией.
create table files						-- Вложенные файлы.
(
	id int not null auto_increment,		-- Идентификатор.
	hash varchar(32) default null,		-- Хеш.
	name varchar(256) not null,			-- Имя.
	size int not null,					-- Размер в байтах.
	thumbnail varchar(256) not null,	-- Уменьшенная копия.
	thumbnail_w int not null,			-- Ширина уменьшенной копии.
	thumbnail_h int not null,			-- Высота уменьшенной копии.
	primary key (id)
)
engine=InnoDB|

create table groups					-- Группы.
(
	id int not null auto_increment,	-- Идентификатор.
	name varchar(50) not null,		-- Имя.
	primary key (id),
	unique key (name)
)
engine=InnoDB|

-- Заметки:
-- Вложенные изображения - файлы изображений, загруженные пользователями и
-- сохранённые на сервере. name и thumbnail - имя файла исходного изображения и
-- файла уменьшенной копии, соответственно. hash - хеш исходного файла.
create table images						-- Вложенные изображения.
(
	id int not null auto_increment,		-- Идентификатор.
	hash varchar(32) default null,		-- Хеш.
	name varchar(256) not null,			-- Имя.
	widht int not null,					-- Ширина.
	height int not null,				-- Высота.
	size int not null,					-- Размер в байтах.
	thumbnail varchar(256) not null,	-- Уменьшенная копия.
	thumbnail_w int not null,			-- Ширина уменьшенной копии.
	thumbnail_h int not null,			-- Высота уменьшенной копии.
	primary key (id)
)
engine=InnoDB|

create table languages				-- Языки.
(
	id int not null auto_increment,	-- Идентификатор.
	code char(3) not null,			-- Код ISO_639-2.
	primary key (id)
)
engine=InnoDB|

create table links						-- Вложенные ссылки на изображения.
(
	id int not null auto_increment,		-- Идентификатор.
	url varchar(2048) not null,			-- URL.
	widht int not null,					-- Ширина.
	height int not null,				-- Высота.
	size int not null,					-- Размер в байтах.
	thumbnail varchar(2048) not null,	-- URL уменьшенной копии.
	thumbnail_w int not null,			-- Ширина уменьшенной копии.
	thumbnail_h int not null,			-- Высота уменьшенной копии.
	primary key (id)
)
engine=InnoDB|

create table popdown_handlers		-- Обработчики автоматического удаления нитей.
(
	id int not null auto_increment,	-- Идентификатор.
	name varchar(50) not null,		-- Имя функции.
	primary key (id)
)
engine=InnoDB|

create table stylesheets			-- Стили.
(
	id int not null auto_increment,	-- Идентификатор.
	name varchar(50) not null,		-- Имя файла.
	primary key (id)
)
engine=InnoDB|

create table upload_handlers		-- Обработчики загружаемых файлов.
(
	id int not null auto_increment,	-- Идентификатор.
	name varchar(50) not null,		-- Имя фукнции.
	primary key (id)
)
engine = InnoDB|

create table videos					-- Вложенные видео.
(
	id int not null auto_increment,	-- Идентификатор.
	code varchar(256) not null,		-- HTML-код.
	widht int not null,				-- Ширина.
	height int not null,			-- Высота.
	primary key (id)
)
engine=InnoDB|

create table boards							-- Доски.
(
	id int not null auto_increment,			-- Идентификатор.
	name varchar(16) not null,				-- Имя.
	title varchar(50) default null,			-- Заголовок.
	annotation text default null,			-- Аннотация.
	bump_limit int not null,				-- Специфичный для доски бамплимит.
	force_anonymous bit not null,			-- Флаг отображения имени отправителя.
	default_name varchar(128) default null,	-- Имя отправителя по умолчанию.
	-- Этот флаг не может быть null, так как для него нет родительского
	-- значения, которое можно было бы унаследовать.
	with_attachments bit not null,			-- Флаг вложений.
	enable_macro bit default null,			-- Включение интеграции с макрочаном.
	enable_youtube bit default null,		-- Включение вложения видео с ютуба.
	enable_captcha bit default null,		-- Включение капчи.
	same_upload varchar(32) not null,		-- Политика загрузки одинаковых файлов.
	popdown_handler int not null,			-- Обработчик автоматического удаления нитей.
	category int not null,					-- Категория.
  	primary key (id),
	unique key (name),
	constraint foreign key (category) references categories (id) on delete restrict on update restrict,
	constraint foreign key (popdown_handler) references popdown_handlers (id) on delete restrict on update restrict
)
engine=InnoDB|

create table users
(
	id int not null auto_increment,
	keyword varchar(32) default null,
	posts_per_thread int default null,
	threads_per_page int default null,
	lines_per_post int default null,
	language int not null,
	stylesheet int not null,
	password varchar(12) default null,
	`goto` varchar(32) default null,
	primary key (id),
	unique key (keyword),
	constraint foreign key (language) references languages (id) on delete restrict on update restrict,
	constraint foreign key (stylesheet) references stylesheets (id) on delete restrict on update restrict
)
engine=InnoDB|

create table user_groups
(
	user int not null,
	`group` int not null,
	constraint foreign key (`group`) references groups (id) on delete cascade on update restrict,
	constraint foreign key (user) references users (id),
	unique key (user, `group`)
) 
engine=InnoDB|

-- Заметки:
-- Имя файла уменьшенной копии типа загружаемых файлов является имя файла
-- изображения. См. заметки к таблице files, описание для поля thumbnail.
create table upload_types						-- Типы загружаемых файлов.
(
	id int not null auto_increment,				-- Идентификатор.
	extension varchar(10) not null,				-- Расширение.
	store_extension varchar(10) default null,	-- Сохраняемое расширение.
	is_image bit not null,						-- Флаг изображения.
	upload_handler int not null,				-- Идентификатор обработчика загружаемого файла.
	thumbnail_image varchar(256) default null,	-- Имя файла уменьшенной копии.
	primary key (id),
	constraint foreign key (upload_handler) references upload_handlers (id) on delete restrict on update restrict,
	unique key (extension)
)
engine=InnoDB|

create table board_upload_types	-- Связь досок и типов загружаемых файлов.
(
	board int not null,			-- Доска.
	upload_type int not null,	-- Тип загружаемого файла.
	constraint foreign key (board) references boards (id) on delete restrict on update restrict,
	constraint foreign key (upload_type) references upload_types (id) on delete restrict on update restrict,
	unique (board, upload_type)
)
engine=InnoDB|

create table threads					-- Нити.
(
	id int not null auto_increment,		-- Идентификатор.
	board int not null,					-- Идентификатор доски.
	original_post int default null,		-- Номер оригинального сообщения.
	bump_limit int default null,		-- Специфичный для нити бамплимит.
	deleted bit not null,				-- Пометка на удаление.
	archived bit not null,				-- Флаг архивирования.
	-- Этот флаг не может быть null, так как для него нет родительского
	-- значения, которое можно было бы унаследовать.
	sage bit not null,					-- Флаг поднятия нити.
	sticky bit not null default 0,		-- Флаг закрепления.
	-- Если этот флаг null, то берётся родительский with_attachments доски.
	with_attachments bit default null,	-- Флаг вложений.
	primary key (id),
	constraint foreign key (board) references boards (id) on delete restrict on update restrict
)
engine=InnoDB|

create table hidden_threads	-- Скрытые нити.
(
	user int,				-- Пользователь.
	thread int,				-- Нить.
	unique key (user, thread),
	constraint foreign key (user) references users (id) on delete restrict on update restrict,
	constraint foreign key (thread) references threads (id) on delete restrict on update restrict
)
engine=InnoDB|

-- Заметки:
-- Если установлен флаг удаления, то сообщение считается "помеченным на
-- удаление".
create table posts						-- Сообщения.
(
	id int not null auto_increment,		-- Идентификатор.
	board int not null,					-- Идентификатор доски.
	thread int not null,				-- Идентификатор нити.
	number int not null,				-- Номер.
	user int not null,					-- Идентификатор пользователя.
	password varchar(12) default null,	-- Пароль.
	name varchar(128) default null,		-- Имя отправителя.
	tripcode varchar(128) default null,	-- Трипкод.
	ip bigint default null,				-- IP-адрес отправителя.
	subject varchar(128) default null,	-- Тема.
	date_time datetime default null,	-- Время сохранения.
	`text` text default null,			-- Текст.
	-- Если этот флаг null, то берётся родительский sage от нити.
	sage bit default null,				-- Флаг поднятия нити.
	deleted bit not null,				-- Флаг удаления.
	primary key (id),
	constraint foreign key (board) references boards (id) on delete restrict on update restrict,
	constraint foreign key (thread) references threads (id) on delete restrict on update restrict,
	constraint foreign key (user) references users (id) on delete restrict on update restrict
)
engine=InnoDB|

create table acl				-- Список контроля доступа.
(
	`group` int default null,	-- Группа.
	board int default null,		-- Доска.
	thread int default null,	-- Нить.
	post int default null,		-- Сообщение.
	`view` bit not null,		-- Право на просмотр.
	`change` bit not null,		-- Право на изменение.
	moderate bit not null,		-- Право на модерирование.
	unique key (`group`, board, thread, post),
	constraint foreign key (`group`) references groups (id)  on delete cascade on update restrict,
	constraint foreign key (board) references boards (id) on delete restrict on update restrict,
	constraint foreign key (thread) references threads (id) on delete restrict on update restrict,
	constraint foreign key (post) references posts (id) on delete restrict on update restrict
)
engine=InnoDB|

create table posts_images
(
	post int not null,
	image int not null,
	deleted bit not null,
	unique key (post, image),
	constraint foreign key (post) references posts (id) on delete restrict on update restrict,
	constraint foreign key (image) references images (id) on delete restrict on update restrict
)
engine=InnoDB|

create table posts_files
(
	post int not null,
	file int not null,
	deleted bit not null,
	unique key (post, file),
	constraint foreign key (post) references posts (id) on delete restrict on update restrict,
	constraint foreign key (file) references files (id) on delete restrict on update restrict
)
engine=InnoDB|

create table posts_links
(
	post int not null,
	link int not null,
	deleted bit not null,
	unique key (post, link),
	constraint foreign key (post) references posts (id) on delete restrict on update restrict,
	constraint foreign key (link) references links (id) on delete restrict on update restrict
)
engine=InnoDB|

create table posts_videos
(
	post int not null,
	video int not null,
	deleted bit not null,
	unique key (post, video),
	constraint foreign key (post) references posts (id) on delete restrict on update restrict,
	constraint foreign key (video) references videos (id) on delete restrict on update restrict
)
engine=InnoDB|