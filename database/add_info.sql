/*Събитие за KN и випуск 2020 -> създател Яна*/
INSERT INTO `eventinfo` (EventName, EventDesc, CreatedEventDateTime, EventImage) 
VALUES ('IT cols', 'Здравейте, колеги! Имам удоволствието да ви поканя на събитието за откриването
на новата ми компания! Ще поговорим за новите тенденции в ИТ индустрията и ще обменим идеи за
бъдещи проекти. Очаквам ви!', '2024-07-12 10:00:00', 'it_comp.jpg');

INSERT INTO `usertoevent` (UserId, EventId, Accepted, Created)
VALUES (2, 1, 1, 1);


/*Събитие за SI и випуск 2021 -> създател Радина*/
INSERT INTO `eventinfo` (EventName, EventDesc, CreatedEventDateTime, EventImage) 
VALUES ('Випуск 2021', 'Здравейте! С колегите ми от специалност "Софтуерно инженерство" ви каним
на събиране на всички колеги от випуск 2021.', '2024-08-1 20:00:00', 'class2021.jpg');

INSERT INTO `usertoevent` (UserId, EventId, Accepted, Created)
VALUES (4, 2, 1, 1);


/*Събитие за всички -> създател Даниела*/
INSERT INTO `eventinfo` (EventName, EventDesc, CreatedEventDateTime, EventImage) 
VALUES ('OpenDoors', 'Здравейте, ИТ машинки! Каним ви на ден на отворените врати в нашата компания VeDaIT. 
Носете своето добро настроение, защото това е едно различно събитие, на което ще се забавляваме!',
 '2024-09-1 17:00:00', 'veda.jpg');

INSERT INTO `usertoevent` (UserId, EventId, Accepted, Created)
VALUES (12, 3, 1, 1);

/*Обява -> създател Велислава*/
INSERT INTO `adinfo` (RecruiterId, AdName, AdDesc, CreatedEventDateTime) 
VALUES (11, 'Senior Dev', "Екипът на VeDaIT търси мотивиран кандидат, който има 3+ години опит с Python, Unix/Linux,
Agile Methodologies.", NOW());

/*Обява -> създател Андрей*/
INSERT INTO `adinfo` (RecruiterId, AdName, AdDesc, CreatedEventDateTime) 
VALUES (10, 'Junior Ops', "Търсим мотивиран кандидат с опит с GitHub или Gitlab и познания в Kubernetes, OpenShift и Docker.",
 NOW());