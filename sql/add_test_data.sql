INSERT INTO Member
	(name, password)
	VALUES ('Käyttäjä', 'Käyttäjä');

INSERT INTO Team
	(name, admin)
	VALUES ('Käyttäjät', false);

INSERT INTO MemberTeam
	(member, team)
	VALUES (1, 1);

INSERT INTO Member
	(name, password)
	VALUES ('Admin', 'Admin');

INSERT INTO Team
	(name, admin)
	VALUES ('Ylläpitäjät', true);

INSERT INTO MemberTeam
	(member, team)
	VALUES (2, 2);

INSERT INTO Member
	(name, password)
	VALUES ('Käyttäjä2', 'Käyttäjä2');

INSERT INTO MemberTeam
	(member, team)
	VALUES (3, 1);

INSERT INTO Category
	(name)
	VALUES ('Testikategoria');

INSERT INTO Category
	(name)
	VALUES ('Testikategoria 2');

INSERT INTO Area
	(category, name, description)
	VALUES (1, 'Testialue', 'Lorem ipsum');

INSERT INTO Area
	(category, name, description)
	VALUES (1, 'Testialue 2', 'Dolor sit amet');

INSERT INTO Area
	(category, name, description)
	VALUES (2, 'Testialue 3', 'Lorem ipsum');

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
	(topic, member, title, content, time)
	VALUES (1, 1, 'Testiviesti', 'Lorem ipsum dolor sit amet', to_timestamp('2008-10-19 10:54:45', 'YYYY-MM-DD HH24:MI:SS'));

INSERT INTO Message
	(topic, member, title, content, time)
	VALUES (2, 1, 'Testiviesti 2', 'Lorem ipsum dolor sit amet', to_timestamp('2008-10-19 15:54:45', 'YYYY-MM-DD HH24:MI:SS'));

INSERT INTO Message
	(topic, member, title, content, time)
	VALUES (3, 1, 'Testiviesti 3', 'Lorem ipsum dolor sit amet', to_timestamp('2008-15-19 15:22:45', 'YYYY-MM-DD HH24:MI:SS'));

INSERT INTO Message
	(topic, member, title, content, time)
	VALUES (3, 1, 'Testiviesti 4', 'Lorem ipsum', to_timestamp('2008-15-19 15:25:23', 'YYYY-MM-DD HH24:MI:SS'));