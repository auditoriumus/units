Правила по базе данных:

База данный: units содержит две таблицы users (
id tinyint(3) UNSIGNED AUTO_INCREMENT
email varchar(255)
hashpwd	varchar(255)
name varchar(50)	NULL
surname	varchar(50)	NULL
about	text
avatar	varchar(255)
) и users_ip (
id tinyint(3) AUTO_INCREMENT
ip varchar(15) NULL
entry_time	int(11) NULL	
)

Подключение к базе данных настраивается в app/Init.php в конструкторе (__construct)
