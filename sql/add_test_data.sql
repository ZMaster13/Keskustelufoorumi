INSERT INTO Member
	(name, password, hash)
	VALUES ('Käyttäjä', 'Käyttäjä', 'asdf');


INSERT INTO Team
	(name, admin)
	VALUES ('Käyttäjät', false);

INSERT INTO MemberTeam
	(member, team)
	VALUES (1, 1);

INSERT INTO Category
	(name)
	VALUES ('Testikategoria');

INSERT INTO Category
	(name)
	VALUES ('Testikategoria 2');

INSERT INTO Area
	(category, name)
	VALUES (1, 'Testialue');

INSERT INTO Area
	(category, name)
	VALUES (1, 'Testialue 2');

INSERT INTO Topic
	(area, member, name)
	VALUES (1, 1, 'Testiaihe');

INSERT INTO Topic
	(area, member, name)
	VALUES (1, 1, 'Testiaihe 2');

INSERT INTO Topic
	(area, member, name)
	VALUES (1, 1, 'Testiaihe 3');

INSERT INTO Message
	(topic, member, title, message, time)
	VALUES (1, 1, 'Testiviesti', 'Lorem ipsum dolor sit amet', to_timestamp('2008-10-19 10:54:45', 'YYYY-MM-DD HH24:MI:SS'));

INSERT INTO Message
	(topic, member, title, message, time)
	VALUES (1, 1, 'Testiviesti 2', 'Lorem ipsum dolor sit amet', to_timestamp('2008-10-19 15:54:45', 'YYYY-MM-DD HH24:MI:SS'));

INSERT INTO Message
	(topic, member, title, message, time)
	VALUES (2, 1, 'Testiviesti 3', 'Lorem ipsum dolor sit amet', to_timestamp('2008-15-19 15:22:45', 'YYYY-MM-DD HH24:MI:SS'));