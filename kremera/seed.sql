-- =====================================================================
-- Kremera — seed das customizacoes do MyAAC (versionado p/ git)
-- ---------------------------------------------------------------------
-- Reproduz num DB do MyAAC tudo o que NAO vive em arquivo (e por isso nao
-- ia pro git): settings, menus do template kathrine, paginas custom
-- (rules/downloads) e a noticia de boas-vindas.
--
-- Aplicar (a partir da raiz do repo myaac):
--   docker exec -i crystalserver-database-1 \
--     mariadb --default-character-set=utf8mb4 -ucrystalserver -p<SENHA_DB> \
--     crystalserver < kremera/seed.sql
--
-- Idempotente: cada secao apaga as proprias linhas antes de inserir, entao
-- pode rodar de novo sem duplicar. UTF-8/emoji exigem --default-character-set=utf8mb4.
--
-- NAO coberto aqui (fora do DB):
--   * Branding (logo.png, style.css) -> versionado em templates/kathrine/ (repo).
--   * Nome do site "Kremera" -> $config['lua']['serverName'] = 'Kremera'; em
--     config.local.php (gitignored). Ver kremera/README.md.
-- =====================================================================
SET NAMES utf8mb4;

-- ── 0) COLUNAS QUE O MYAAC ESPERA NAS TABELAS DO JOGO ────────────────
-- Este MyAAC foi apontado para um DB Canary/crystalserver existente sem rodar
-- a migracao de colunas do instalador (install/tools/5-database.php). Sem elas,
-- paginas como highscores/online quebram (ex.: Unknown column 'accounts.country').
-- Sao campos proprios do MyAAC (nao afetam o gameplay do Canary). Idempotente.
ALTER TABLE `accounts`
  ADD COLUMN IF NOT EXISTS `key` VARCHAR(64) NOT NULL DEFAULT '',
  ADD COLUMN IF NOT EXISTS `created` INT(11) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `rlname` VARCHAR(255) NOT NULL DEFAULT '',
  ADD COLUMN IF NOT EXISTS `location` VARCHAR(255) NOT NULL DEFAULT '',
  ADD COLUMN IF NOT EXISTS `country` VARCHAR(3) NOT NULL DEFAULT '',
  ADD COLUMN IF NOT EXISTS `web_lastlogin` INT(11) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `web_flags` INT(11) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `email_verified` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `email_new` VARCHAR(255) NOT NULL DEFAULT '',
  ADD COLUMN IF NOT EXISTS `email_new_time` INT(11) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `email_code` VARCHAR(255) NOT NULL DEFAULT '',
  ADD COLUMN IF NOT EXISTS `email_next` INT(11) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `premium_points` INT(11) NOT NULL DEFAULT 0;
ALTER TABLE `players`
  ADD COLUMN IF NOT EXISTS `created` INT(11) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `hide` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `comment` VARCHAR(5000) NOT NULL DEFAULT '';

-- myaac_account_actions.ip precisa ser INT UNSIGNED: o MyAAC grava ip2long(ip) no log de acoes
-- (logAction), e IPs acima de 127.x (todo usuario real, ex.: 172.19.0.1 -> 2.887.254.017) estouram
-- um INT com sinal (max 2.147.483.647) -> "Out of range value for column 'ip'" -> 500 ao CRIAR CONTA.
ALTER TABLE `myaac_account_actions` MODIFY `ip` INT UNSIGNED NOT NULL DEFAULT 0;

-- ── 1) SETTINGS (grupo core) ─────────────────────────────────────────
-- template travado no kremera (tema do handoff Fable); status do game pela rede docker; criacao
-- classless (1 sample None) some o seletor de classe; pais padrao Brasil.
-- CLASSLESS: 'vocations' com indice 0 (None) VAZIO => a pagina do personagem mostra a
-- profissao em branco (nao existe vocacao no Kremera). 'highscores_ids_hidden' esconde do
-- ranking o Rook Sample (id 1), que existe so como MOLDE de criacao de char (GMs group>=3 ja
-- somem por padrao). 'signature_enabled'=0 remove a secao de Signature (estranha no Kremera).
-- 'characters_magic_level'=0 tira a linha "Magic Level" do topo (Magic ja aparece no pool de
-- Combate do painel de skills classless). Pagina ONLINE (classless/sem PvP por ora):
-- 'online_vocations'=0 (sem estatistica de vocacao) e 'online_skulls'=0 (sem legenda de frags/skull).
-- HIGHSCORES: 'highscores_vocation'=0 (sem vocacao sob o nome) e 'highscores_vocation_box'=0
-- (sem a caixa "Choose a vocation"). As categorias de skill viraram as reais do Kremera no
-- system/pages/highscores.php (Melee/Distance/Shield/Magic/Healing + workers via player_storage).
DELETE FROM `myaac_settings` WHERE `name`='core' AND `key` IN
 ('template','template_allow_change','status_ip','character_samples','account_countries_most_popular',
  'vocations','highscores_ids_hidden','signature_enabled','characters_magic_level',
  'online_vocations','online_skulls','highscores_vocation','highscores_vocation_box');
INSERT INTO `myaac_settings` (`name`,`key`,`value`) VALUES
 ('core','template','kremera'),
 ('core','template_allow_change','false'),
 ('core','status_ip','crystal-server'),
 ('core','character_samples','0=Rook Sample'),
 ('core','account_countries_most_popular','br,pt,us,gb'),
 ('core','vocations',', Sorcerer, Druid, Paladin, Knight, Master Sorcerer, Elder Druid, Royal Paladin, Elite Knight'),
 ('core','highscores_ids_hidden','0,1'),
 ('core','signature_enabled','0'),
 ('core','characters_magic_level','0'),
 ('core','online_vocations','0'),
 ('core','online_skulls','0'),
 ('core','highscores_vocation','0'),
 ('core','highscores_vocation_box','0');

-- ── 2) MENUS (template kathrine) ─────────────────────────────────────
-- categorias: 1=News 2=Account 3=Community 5=Library 6=Shop
DELETE FROM `myaac_menu` WHERE `template`='kathrine';
INSERT INTO `myaac_menu` (`template`,`name`,`link`,`blank`,`color`,`category`,`ordering`,`enabled`) VALUES
 ('kathrine','Latest News','news',0,'',1,0,1),
 ('kathrine','News Archive','news/archive',0,'',1,1,1),
 ('kathrine','Changelog','change-log',0,'',1,2,1),
 ('kathrine','Account Management','account/manage',0,'',2,0,1),
 ('kathrine','Create Account','account/create',0,'',2,1,1),
 ('kathrine','Lost Account?','account/lost',0,'',2,2,1),
 ('kathrine','Server Rules','rules',0,'',2,3,1),
 ('kathrine','Downloads','downloads',0,'',2,4,1),
 ('kathrine','Characters','characters',0,'',3,0,1),
 ('kathrine','Who is Online?','online',0,'',3,1,1),
 ('kathrine','Highscores','highscores',0,'',3,2,1),
 ('kathrine','Last Kills','last-kills',0,'',3,3,1),
 ('kathrine','Houses','houses',0,'',3,4,1),
 ('kathrine','Guilds','guilds',0,'',3,5,1),
 ('kathrine','Bans','bans',0,'',3,6,1),
 ('kathrine','Forum','forum',0,'',3,7,1),
 ('kathrine','Team','team',0,'',3,8,1),
 ('kathrine','Monsters','monsters',0,'',5,0,1),
 ('kathrine','Spells','spells',0,'',5,1,1),
 ('kathrine','Server Info','ots-info',0,'',5,2,1),
 ('kathrine','Commands','commands',0,'',5,3,1),
 ('kathrine','Exp Stages','exp-stages',0,'',5,4,1),
 ('kathrine','Gallery','gallery',0,'',5,5,1),
 ('kathrine','Exp Table','exp-table',0,'',5,6,1),
 ('kathrine','FAQ','faq',0,'',5,7,1),
 ('kathrine','Buy Points','points',0,'',6,0,1),
 ('kathrine','Shop Offer','gifts',0,'',6,1,1),
 ('kathrine','Shop History','gifts/history',0,'',6,2,1);

-- ── 3) PAGINAS CUSTOM (rules + downloads), bilingue PT/EN ─────────────
DELETE FROM `myaac_pages` WHERE `name` IN ('rules','downloads');
-- Conteudo em cards do design system (Fable) + blocos .lang-pt/.lang-en
-- (o toggle PT|EN do template alterna a visibilidade via html[lang]).
INSERT INTO `myaac_pages` (`name`,`title`,`body`,`date`,`player_id`,`php`,`enable_tinymce`,`access`,`hide`) VALUES
('rules','Server Rules','<div class="lang-pt"><p class="lead">Para manter Kremera justo e divertido para todos, siga o código abaixo.</p><div class="card"><div class="card-h"><h2>Código de Conduta</h2><span class="hint">a punição fica a critério da equipe</span></div><div class="card-b"><ol class="rulelist"><li><b>Sem cheats ou bots</b><span>Bots, macros, scripts ou qualquer automação de jogo são proibidos.</span></li><li><b>Sem abuso de bugs</b><span>Encontrou uma falha? Reporte à equipe e não a explore.</span></li><li><b>Nomes adequados</b><span>Nomes ofensivos ou que imitem a equipe são proibidos.</span></li><li><b>Respeito</b><span>Sem ofensas, racismo, discurso de ódio ou assédio nos canais.</span></li><li><b>Sua conta, sua responsabilidade</b><span>Não compartilhe nem venda contas.</span></li><li><b>Sem golpes</b><span>Trapacear ou enganar outros jogadores (scam) é proibido.</span></li><li><b>Não se passe pela equipe</b><span>Apenas a staff oficial fala em nome do Kremera.</span></li></ol></div></div><p class="footnote">A equipe pode punir condutas inadequadas mesmo que não estejam listadas aqui.</p></div><div class="lang-en"><p class="lead">To keep Kremera fair and fun for everyone, follow the code below.</p><div class="card"><div class="card-h"><h2>Code of Conduct</h2><span class="hint">punishment is at the team discretion</span></div><div class="card-b"><ol class="rulelist"><li><b>No cheats or bots</b><span>Bots, macros, scripts or any game automation are forbidden.</span></li><li><b>No bug abuse</b><span>Found a bug? Report it to the team and do not exploit it.</span></li><li><b>Proper names</b><span>Offensive names or names impersonating staff are forbidden.</span></li><li><b>Respect</b><span>No insults, racism, hate speech or harassment in the channels.</span></li><li><b>Your account, your responsibility</b><span>Do not share or sell accounts.</span></li><li><b>No scamming</b><span>Cheating or deceiving other players is forbidden.</span></li><li><b>Do not impersonate staff</b><span>Only official staff speaks for Kremera.</span></li></ol></div></div><p class="footnote">The team may punish inappropriate behavior even if it is not listed here.</p></div>',UNIX_TIMESTAMP(),0,0,1,0,0),
('downloads','Downloads','<div class="lang-pt"><div class="callout"><p><b>⚠️ O download ainda não está disponível.</b> Estamos finalizando o client — volte em breve. Assim que liberado, o link aparecerá aqui.</p><a class="btn btn-gold btn-sm" href="index.php/account/create">Criar Conta</a></div><div class="card"><div class="card-h"><h2>Client do Kremera</h2></div><div class="card-b"><p>O Kremera usa um <b>client próprio</b>, baseado no OTClient 15.24 e já preparado para o servidor.</p><p>Enquanto o download não abre, crie sua conta e seu personagem para já chegar pronto no lançamento.</p></div></div></div><div class="lang-en"><div class="callout"><p><b>⚠️ The download is not available yet.</b> We are finishing the client — check back soon. Once released, the link will appear here.</p><a class="btn btn-gold btn-sm" href="index.php/account/create">Create Account</a></div><div class="card"><div class="card-h"><h2>Kremera Client</h2></div><div class="card-b"><p>Kremera uses a <b>custom client</b>, based on OTClient 15.24 and ready for the server.</p><p>While the download is not open yet, create your account and character so you arrive ready at launch.</p></div></div></div>',UNIX_TIMESTAMP(),0,0,1,0,0);

-- ── 4) NOTICIA de boas-vindas (categoria Geral + post) ───────────────
DELETE FROM `myaac_news` WHERE `title`='Bem-vindo ao Kremera! · Welcome to Kremera!';
DELETE FROM `myaac_news_categories` WHERE `name`='Geral';
INSERT INTO `myaac_news_categories` (`name`,`description`,`icon_id`,`hide`) VALUES
 ('Geral','Noticias gerais / General',0,0);
SET @cat = LAST_INSERT_ID();
INSERT INTO `myaac_news` (`title`,`body`,`type`,`date`,`category`,`player_id`,`last_modified_by`,`last_modified_date`,`comments`,`article_text`,`article_image`,`hide`) VALUES
 ('Bem-vindo ao Kremera! · Welcome to Kremera!','<h3>🇧🇷 Bem-vindo ao Kremera!</h3><p>Kremera é um MMORPG <b>sem classes</b>: você não escolhe uma vocação — fica mais forte <b>treinando</b> suas skills de combate e de profissão (mineração, lenha, alquimia, ferraria e mais). Crie sua conta, faça seu personagem e comece pela ilha tutorial em <b>New Haven</b>, onde o Elton te guia. Boa jornada!</p><hr/><h3>🇺🇸 Welcome to Kremera!</h3><p>Kremera is a <b>classless</b> MMORPG: you do not pick a vocation — you grow stronger by <b>training</b> your combat and profession skills (mining, woodcutting, alchemy, blacksmithing and more). Create your account, make your character, and begin on the tutorial island in <b>New Haven</b>, where Elton guides you. Enjoy your journey!</p>',1,UNIX_TIMESTAMP(),@cat,0,0,UNIX_TIMESTAMP(),'','','',0);
