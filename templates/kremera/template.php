<?php
defined('MYAAC') or die('Direct access not allowed!');

// Página atual (home = news). O shell renderiza a landing full-bleed na home
// e um layout em "caixa" (banner + conteúdo) nas páginas internas do portal.
$kremera_page = isset($page) ? $page : (defined('PAGE') ? PAGE : '');
$isHome = ($kremera_page === '' || $kremera_page === 'news');

$onlineCount = (isset($status['online']) && $status['online']) ? (int)$status['players'] : 0;
$serverName = $config['lua']['serverName'] ?? 'Kremera';

// Links do MyAAC
$L = [
	'home'      => getLink('news'),
	'create'    => getLink('account/create'),
	'account'   => getLink('account/manage'),
	'download'  => getLink('downloads'),
	'characters'=> getLink('characters'),
	'highscores'=> getLink('highscores'),
	'online'    => getLink('online'),
	'news'      => getLink('news'),
];
$img = $template_path . '/images';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $isHome ? ($serverName . ' — MMORPG old-school classless') : (isset($title_full) ? $title_full : $serverName); ?></title>
	<?php echo template_place_holder('head_start'); ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;900&family=Alegreya:ital,wght@0,400;0,500;0,700;1,400&family=IM+Fell+English:ital@1&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo $template_path; ?>/style.css?v=4" type="text/css" />
	<?php echo template_place_holder('head_end'); ?>
</head>
<body>
<?php echo template_place_holder('body_start'); ?>

<?php if ($isHome): ?>
<header class="topbar">
	<div class="wrap">
		<a class="logo" href="<?php echo $L['home']; ?>" aria-label="<?php echo $serverName; ?>"><img src="<?php echo $img; ?>/logo-nav.webp" alt="<?php echo $serverName; ?>"></a>
		<nav class="nav" aria-label="principal">
			<a href="#jogo" data-i18n="nav.game"></a>
			<a href="#skills" data-i18n="nav.skills"></a>
			<a href="#mundo" data-i18n="nav.world"></a>
			<a href="#comecar" data-i18n="nav.start"></a>
		</nav>
		<div class="topbar-actions">
			<div class="lang" role="group" aria-label="Idioma">
				<button id="btn-pt" class="on" onclick="setLang('pt')">PT</button><span>|</span><button id="btn-en" onclick="setLang('en')">EN</button>
			</div>
			<a class="btn btn-ghost btn-sm" href="<?php echo $L['download']; ?>" data-i18n="nav.download"></a>
			<a class="btn btn-gold btn-sm" href="<?php echo $L['create']; ?>" data-i18n="nav.register"></a>
		</div>
	</div>
</header>
<?php endif; ?>

<?php if ($isHome): ?>
<!-- ==================== LANDING ==================== -->
<main>
<section class="hero" id="top">
	<div class="hero-bg"><img src="<?php echo $img; ?>/hero-bg.webp" alt=""></div>
	<div class="hero-inner">
		<img class="hero-logo" src="<?php echo $img; ?>/logo-full.webp" alt="<?php echo $serverName; ?>">
		<span class="eyebrow" data-i18n="hero.eyebrow"></span>
		<h1 data-i18n="hero.title"></h1>
		<p data-i18n="hero.subtitle"></p>
		<div class="hero-cta">
			<a class="btn btn-gold" href="<?php echo $L['download']; ?>" data-i18n="hero.ctaPrimary"></a>
			<a class="btn btn-ghost" href="<?php echo $L['create']; ?>" data-i18n="hero.ctaSecondary"></a>
		</div>
		<div class="hero-status"><span class="pulse" aria-hidden="true"></span><span data-i18n="hero.status"></span></div>
	</div>
	<a class="hero-scroll" href="#jogo" aria-label="rolar"><img class="px" src="<?php echo $img; ?>/ui/state-down.png" alt=""></a>
</section>

<section id="jogo">
	<div class="wrap">
		<div class="section-head">
			<span class="eyebrow" data-i18n="pillars.eyebrow"></span>
			<h2 data-i18n="pillars.title"></h2>
			<div class="divider"><img class="px" src="<?php echo $img; ?>/ui/dot.png" alt=""></div>
		</div>
		<div class="pillars">
			<div class="frame pillar">
				<svg viewBox="0 0 54 54" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true">
					<path d="M12 42 L38 12 M34 10 l6 -2 -2 6z M12 42 l-3 3 M9 39 l6 6"/>
					<path d="M42 42 L20 16 M20 16 v-6 M17 8 a3 3 0 1 1 6 0 a3 3 0 1 1 -6 0 M42 42 l3 3 M45 39 l-6 6"/>
				</svg>
				<h3 data-i18n="pillars.classless.title"></h3>
				<p data-i18n="pillars.classless.body"></p>
			</div>
			<div class="frame pillar">
				<svg viewBox="0 0 54 54" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M27 6 L44 12 V26 C44 37 36 44 27 48 C18 44 10 37 10 26 V12 Z"/>
					<path d="M27 14 v26 M16 22 h22" opacity=".6"/>
				</svg>
				<h3 data-i18n="pillars.oldschool.title"></h3>
				<p data-i18n="pillars.oldschool.body"></p>
			</div>
			<div class="frame pillar">
				<svg viewBox="0 0 54 54" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M27 8 v38 M20 46 h14 M13 14 h28"/>
					<path d="M13 14 l-6 14 h12 z M6 28 a7 5 0 0 0 14 0"/>
					<path d="M41 14 l-6 14 h12 z M35 28 a7 5 0 0 0 14 0"/>
				</svg>
				<h3 data-i18n="pillars.world.title"></h3>
				<p data-i18n="pillars.world.body"></p>
			</div>
		</div>
	</div>
</section>

<section class="alt" id="skills">
	<div class="wrap">
		<div class="skills-grid">
			<div class="skills-copy">
				<span class="eyebrow" data-i18n="skills.eyebrow"></span>
				<h2 data-i18n="skills.title"></h2>
				<p data-i18n="skills.body1"></p>
				<p data-i18n="skills.body2"></p>
			</div>
			<div class="frame">
				<div class="sheet-title"><img class="px" src="<?php echo $img; ?>/ui/dot.png" alt=""><span data-i18n="skills.panelTitle"></span></div>
				<div id="sheet"></div>
				<div class="legend">
					<span><img class="px" src="<?php echo $img; ?>/ui/state-up.png" alt=""><span data-i18n="skills.stateUp"></span></span>
					<span><img class="px" src="<?php echo $img; ?>/ui/state-lock.png" alt=""><span data-i18n="skills.stateLock"></span></span>
					<span><img class="px" src="<?php echo $img; ?>/ui/state-down.png" alt=""><span data-i18n="skills.stateDown"></span></span>
				</div>
				<p class="sheet-note" data-i18n="skills.note"></p>
			</div>
		</div>
	</div>
</section>

<section id="rates">
	<div class="wrap">
		<div class="section-head">
			<span class="eyebrow" data-i18n="rates.eyebrow"></span>
			<h2 data-i18n="rates.title"></h2>
			<div class="divider"><img class="px" src="<?php echo $img; ?>/ui/dot.png" alt=""></div>
		</div>
		<div class="rates-grid">
			<div class="frame rate"><div class="rv">15</div><div class="rl" data-i18n="rates.skills"></div><div class="rs" data-i18n="rates.skillsSub"></div></div>
			<div class="frame rate"><div class="rv">200</div><div class="rl" data-i18n="rates.poolCombat"></div><div class="rs" data-i18n="rates.poolSub"></div></div>
			<div class="frame rate"><div class="rv">400</div><div class="rl" data-i18n="rates.poolWorker"></div><div class="rs" data-i18n="rates.poolSub"></div></div>
			<div class="frame rate"><div class="rv">450 oz</div><div class="rl" data-i18n="rates.cap"></div><div class="rs" data-i18n="rates.capSub"></div></div>
			<div class="frame rate"><div class="rv">250</div><div class="rl" data-i18n="rates.hp"></div><div class="rs" data-i18n="rates.vitalSub"></div></div>
			<div class="frame rate"><div class="rv">150</div><div class="rl" data-i18n="rates.mana"></div><div class="rs" data-i18n="rates.vitalSub"></div></div>
			<div class="frame rate"><div class="rv">×?</div><div class="rl" data-i18n="rates.exp"></div><div class="rs" data-i18n="rates.tbd"></div></div>
			<div class="frame rate"><div class="rv">×?</div><div class="rl" data-i18n="rates.loot"></div><div class="rs" data-i18n="rates.tbd"></div></div>
			<div class="frame rate"><div class="rv">×?</div><div class="rl" data-i18n="rates.spawn"></div><div class="rs" data-i18n="rates.tbd"></div></div>
		</div>
		<p class="rates-note" data-i18n="rates.protoNote"></p>
	</div>
</section>

<section class="lore" id="mundo">
	<div class="lore-bg" style="background-image:url('<?php echo $img; ?>/lore-bg.webp')"></div>
	<div class="lore-inner">
		<span class="lore-mark" aria-hidden="true">&ldquo;</span>
		<blockquote data-i18n="lore.quote"></blockquote>
		<p class="lore-attr" data-i18n="lore.attribution"></p>
		<p class="lore-body" data-i18n="lore.body"></p>
	</div>
</section>
</main>

<?php else: ?>
<!-- ==================== PORTAL INTERNO — menu LATERAL + conteúdo ==================== -->
<?php
	$cur = (string) $kremera_page;
	// [chave-de-página, href, chave-i18n] — "chave-de-página" casa por prefixo com a página atual
	$sidePlay = [
		['characters',  $L['characters'], 'portal.characters'],
		['highscores',  $L['highscores'], 'portal.highscores'],
		['online',      $L['online'],     'portal.online'],
		['account',     $L['account'],    'portal.account'],
	];
	$sideInfo = [
		['rules',      getLink('rules'), 'portal.rules'],
		['downloads',  $L['download'],   'portal.downloads'],
	];
?>
<div class="portal-shell">
	<aside class="portal-side">
		<a class="side-logo" href="<?php echo $L['home']; ?>" aria-label="<?php echo $serverName; ?>">
			<img src="<?php echo $img; ?>/logo-nav.webp" alt="<?php echo $serverName; ?>">
		</a>
		<nav class="side-nav" aria-label="portal">
			<a class="side-home" href="<?php echo $L['home']; ?>" data-i18n="portal.home"></a>
			<span class="side-group" data-i18n="portal.sectionPlay"></span>
			<?php foreach ($sidePlay as $it): $act = ($it[0] !== '' && strpos($cur, $it[0]) === 0); ?>
			<a href="<?php echo $it[1]; ?>"<?php echo $act ? ' class="active" aria-current="page"' : ''; ?> data-i18n="<?php echo $it[2]; ?>"></a>
			<?php endforeach; ?>
			<span class="side-group" data-i18n="portal.sectionInfo"></span>
			<?php foreach ($sideInfo as $it): $act = ($it[0] !== '' && strpos($cur, $it[0]) === 0); ?>
			<a href="<?php echo $it[1]; ?>"<?php echo $act ? ' class="active" aria-current="page"' : ''; ?> data-i18n="<?php echo $it[2]; ?>"></a>
			<?php endforeach; ?>
		</nav>
		<div class="side-foot">
			<a class="btn btn-gold btn-sm side-cta" href="<?php echo $L['create']; ?>" data-i18n="nav.register"></a>
			<div class="lang" role="group" aria-label="Idioma">
				<button id="btn-pt" class="on" onclick="setLang('pt')">PT</button><span>|</span><button id="btn-en" onclick="setLang('en')">EN</button>
			</div>
		</div>
	</aside>
	<div class="portal-body">
		<div class="page-banner" style="--banner-img:url('<?php echo $img; ?>/lore-bg.webp')">
			<div class="wrap">
				<span class="eyebrow" data-i18n="portal.eyebrow"></span>
				<h1><?php echo isset($title) ? $title : $serverName; ?></h1>
			</div>
		</div>
		<div class="portal-content">
			<div class="wrap">
				<?php echo function_exists('tickers') ? tickers() : ''; ?>
				<?php echo template_place_holder('center_top'); ?>
				<?php echo $content; ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<footer class="site-footer" id="comecar">
	<div class="wrap">
		<div class="divider"><img class="px" src="<?php echo $img; ?>/ui/dot.png" alt=""></div>
		<img class="flogo" src="<?php echo $img; ?>/logo-nav.webp" alt="<?php echo $serverName; ?>">
		<p class="legal" data-i18n="footer.legal"></p>
		<p class="rights" data-i18n="footer.rights"></p>
		<p class="myaac-credit"><?php echo template_footer(); ?></p>
	</div>
</footer>

<?php echo template_place_holder('body_end'); ?>
<script>
const ONLINE_COUNT = <?php echo $onlineCount; ?>;
const I18N = {
  pt: {
    "nav.game":"O Jogo","nav.skills":"Skills & Rates","nav.world":"O Mundo","nav.start":"Como Começar",
    "nav.download":"Baixar Client","nav.register":"Criar Conta",
    "portal.characters":"Personagens","portal.highscores":"Highscores","portal.online":"Online","portal.news":"Notícias","portal.account":"Conta","portal.eyebrow":"Portal do jogador",
    "portal.home":"‹ Voltar ao site","portal.rules":"Regras","portal.downloads":"Downloads","portal.sectionPlay":"Jogar","portal.sectionInfo":"Informações",
    "hero.eyebrow":"MMORPG old-school · classless · PT/EN",
    "hero.title":"Um outro jeito de jogar MMORPG",
    "hero.subtitle":"Sem vocações. Sem trilhos. Em Kremera, um mundo 2D old-school herdeiro de Tibia e Ultima Online, o seu personagem é a soma das skills que você treina — e cada escolha tem peso.",
    "hero.ctaPrimary":"Baixar o client","hero.ctaSecondary":"Criar conta grátis",
    "hero.status":"{count} jogadores online · Protocolo 15.24",
    "pillars.eyebrow":"O jogo",
    "pillars.title":"Esqueça tudo o que você sabe sobre vocações",
    "pillars.classless.title":"Classless",
    "pillars.classless.body":"Aqui não existe knight, paladin, sorcerer ou druid. Espada, magia, escudo e arco vivem no mesmo personagem: você é aquilo que treina — e nada além disso.",
    "pillars.oldschool.title":"Old-school de verdade",
    "pillars.oldschool.body":"Perspectiva 2D clássica, morte com consequência e conquistas que custam suor. A tensão que os MMORPGs modernos esqueceram, com a profundidade que os clássicos prometeram.",
    "pillars.world.title":"Um mundo com dois rostos",
    "pillars.world.body":"Ao cruzar a ponte, você encontrará a pobreza onde há luxúria — e a luxúria onde há pobreza. Kremera é um reino tomado pela ganância, e cabe a você decidir de que lado da ponte vai viver.",
    "skills.eyebrow":"Sistema classless",
    "skills.title":"Suas skills contam a sua história",
    "skills.body1":"Em Kremera, a vocação foi removida do jogo. São 15 habilidades divididas em dois pools — Combate e Trabalho —, cada uma indo de 0 a 100. Mas o pool tem um teto: 200 pontos no Combate, 400 no Trabalho. Não dá para maximizar tudo — você distribui, e é essa distribuição que define o seu personagem.",
    "skills.body2":"Marque cada skill como subir, travar ou reduzir: com o pool cheio, evoluir uma arte drena outra que você escolheu sacrificar. Um espadachim ferreiro? Um arqueiro alquimista? Um curandeiro que domina o mercado? Construa — ponto a ponto.",
    "skills.panelTitle":"Ficha de habilidades",
    "skills.note":"Qualquer personagem pode treinar qualquer skill — o limite é o pool.",
    "skills.stateUp":"Subir","skills.stateLock":"Travada","skills.stateDown":"Reduzir",
    "skills.poolCombat":"Combate","skills.poolWorker":"Trabalho",
    "skills.names":{"melee":"Corpo a Corpo","distance":"Distância","shield":"Escudo","magic":"Magia","healing":"Cura","fishing":"Pesca","mining":"Mineração","lumber":"Corte de Lenha","harvesting":"Colheita","skinning":"Esfola","cooking":"Cozinha","alchemy":"Alquimia","tailoring":"Alfaiataria","blacksmithing":"Ferraria","carpentry":"Carpintaria"},
    "rates.eyebrow":"Números do mundo",
    "rates.title":"Rates & progressão",
    "rates.exp":"Experiência","rates.loot":"Loot","rates.spawn":"Spawn",
    "rates.skills":"Skills","rates.skillsSub":"2 pools · 0–100 cada",
    "rates.poolCombat":"Pool de Combate","rates.poolWorker":"Pool de Trabalho","rates.poolSub":"pontos somados",
    "rates.cap":"Cap inicial","rates.capSub":"+10 oz por nível",
    "rates.hp":"HP inicial","rates.mana":"Mana inicial","rates.vitalSub":"+5 por nível",
    "rates.tbd":"em definição",
    "rates.protoNote":"* Vitais base por nível — bônus derivados das skills somam por cima. Rates de experiência, loot e spawn serão anunciados.",
    "lore.quote":"“Kremera é tudo que se pode ver... por muito tempo foi um lar pacífico, mas a ganância e a fome de poder tomaram conta deste mundo. Ao passar da ponte, você encontrará a pobreza onde se há luxúria, e a luxúria onde se há pobreza.”",
    "lore.attribution":"— Claudios, monge de Frismera",
    "lore.body":"Toda jornada começa em Frismera, a cidade-tutorial onde iniciantes aprendem a sobreviver antes de cruzar a ponte rumo ao mundo aberto — um reino de castelos dourados, vilas famintas e ruínas arcanas que guardam mais perguntas do que respostas.",
    "footer.legal":"Kremera é um projeto independente e sem fins comerciais, criado por fãs. Não possui qualquer afiliação com a CipSoft GmbH. Tibia é uma marca registrada da CipSoft GmbH.",
    "footer.rights":"© {year} Kremera. Todos os direitos sobre a identidade visual reservados."
  },
  en: {
    "nav.game":"The Game","nav.skills":"Skills & Rates","nav.world":"The World","nav.start":"Getting Started",
    "nav.download":"Download Client","nav.register":"Create Account",
    "portal.characters":"Characters","portal.highscores":"Highscores","portal.online":"Online","portal.news":"News","portal.account":"Account","portal.eyebrow":"Player portal",
    "portal.home":"‹ Back to site","portal.rules":"Rules","portal.downloads":"Downloads","portal.sectionPlay":"Play","portal.sectionInfo":"Information",
    "hero.eyebrow":"Old-school MMORPG · classless · PT/EN",
    "hero.title":"A different way to play MMORPGs",
    "hero.subtitle":"No vocations. No rails. In Kremera — an old-school 2D world descended from Tibia and Ultima Online — your character is the sum of the skills you train, and every choice carries weight.",
    "hero.ctaPrimary":"Download the client","hero.ctaSecondary":"Create a free account",
    "hero.status":"{count} players online · Protocol 15.24",
    "pillars.eyebrow":"The game",
    "pillars.title":"Forget everything you know about vocations",
    "pillars.classless.title":"Classless",
    "pillars.classless.body":"There is no knight, paladin, sorcerer or druid here. Blade, magic, shield and bow live in the same character: you are what you train — nothing more, nothing less.",
    "pillars.oldschool.title":"Truly old-school",
    "pillars.oldschool.body":"Classic 2D perspective, death with consequences and hard-earned progress. The tension modern MMORPGs forgot, with the depth the classics promised.",
    "pillars.world.title":"A world with two faces",
    "pillars.world.body":"Beyond the bridge you will find poverty where there is luxury — and luxury where there is poverty. Kremera is a realm consumed by greed, and it is up to you to decide on which side of the bridge you will live.",
    "skills.eyebrow":"Classless system",
    "skills.title":"Your skills tell your story",
    "skills.body1":"In Kremera, vocations were removed from the game. There are 15 skills split into two pools — Combat and Worker — each ranging from 0 to 100. But every pool has a ceiling: 200 points in Combat, 400 in Worker. You can't max everything — you distribute, and that distribution is what defines your character.",
    "skills.body2":"Mark each skill to raise, lock or lower: when the pool is full, advancing one art drains another you chose to sacrifice. A blacksmith swordsman? An alchemist archer? A healer who rules the market? Build it — point by point.",
    "skills.panelTitle":"Skill sheet",
    "skills.note":"Any character can train any skill — the pool is the limit.",
    "skills.stateUp":"Raise","skills.stateLock":"Locked","skills.stateDown":"Lower",
    "skills.poolCombat":"Combat","skills.poolWorker":"Worker",
    "skills.names":{"melee":"Melee","distance":"Distance","shield":"Shield","magic":"Magic","healing":"Healing","fishing":"Fishing","mining":"Mining","lumber":"Lumberjacking","harvesting":"Harvesting","skinning":"Skinning","cooking":"Cooking","alchemy":"Alchemy","tailoring":"Tailoring","blacksmithing":"Blacksmithing","carpentry":"Carpentry"},
    "rates.eyebrow":"World numbers",
    "rates.title":"Rates & progression",
    "rates.exp":"Experience","rates.loot":"Loot","rates.spawn":"Spawn",
    "rates.skills":"Skills","rates.skillsSub":"2 pools · 0–100 each",
    "rates.poolCombat":"Combat pool","rates.poolWorker":"Worker pool","rates.poolSub":"points combined",
    "rates.cap":"Starting cap","rates.capSub":"+10 oz per level",
    "rates.hp":"Starting HP","rates.mana":"Starting mana","rates.vitalSub":"+5 per level",
    "rates.tbd":"to be announced",
    "rates.protoNote":"* Base vitals per level — skill-derived bonuses stack on top. Experience, loot and spawn rates to be announced.",
    "lore.quote":"“Kremera is all the eye can see... for a long age it was a peaceful home, but greed and the hunger for power have taken hold of this world. Beyond the bridge, you will find poverty where there is luxury, and luxury where there is poverty.”",
    "lore.attribution":"— Claudios, monk of Frismera",
    "lore.body":"Every journey begins in Frismera, the tutorial city where newcomers learn to survive before crossing the bridge into the open world — a realm of gilded castles, starving villages and arcane ruins that hold more questions than answers.",
    "footer.legal":"Kremera is an independent, non-commercial project made by fans. It is not affiliated with CipSoft GmbH in any way. Tibia is a registered trademark of CipSoft GmbH.",
    "footer.rights":"© {year} Kremera. All rights to the visual identity reserved."
  }
};

// Ficha de skills da landing — valores/estados ilustrativos (2 pools, 15 skills, 0–100)
const STATE_ICON = { up:"<?php echo $img; ?>/ui/state-up.png", lock:"<?php echo $img; ?>/ui/state-lock.png", down:"<?php echo $img; ?>/ui/state-down.png" };
const POOLS = [
  { key:"skills.poolCombat", cap:200, skills:[
    ["melee",78,"up"],["shield",64,"up"],["magic",31,"lock"],["healing",15,"lock"],["distance",12,"down"] ]},
  { key:"skills.poolWorker", cap:400, skills:[
    ["blacksmithing",85,"up"],["mining",72,"up"],["skinning",41,"lock"],["lumber",35,"lock"],
    ["carpentry",27,"lock"],["harvesting",22,"lock"],["fishing",18,"lock"],["cooking",12,"down"],
    ["alchemy",8,"lock"],["tailoring",0,"lock"] ]}
];
function renderSheet(lang){
  const sheet = document.getElementById('sheet');
  if(!sheet) return;
  const N = I18N[lang]["skills.names"];
  sheet.innerHTML = POOLS.map(p=>{
    const sum = p.skills.reduce((a,s)=>a+s[1],0);
    return `<div class="pool-head"><b>${I18N[lang][p.key]}</b><span><em>${sum}</em> / ${p.cap}</span></div>` +
      p.skills.map(([k,v,st])=>
        `<div class="skill-row"><span class="sic"><img class="px" src="${STATE_ICON[st]}" alt=""></span><span class="sname">${N[k]}</span><span class="sbar"><i style="width:${v}%"></i></span><span class="slvl">${v}</span></div>`
      ).join('');
  }).join('');
}
function setLang(lang){
  document.documentElement.lang = lang;
  document.getElementById('btn-pt').classList.toggle('on', lang==='pt');
  document.getElementById('btn-en').classList.toggle('on', lang==='en');
  document.querySelectorAll('[data-i18n]').forEach(el=>{
    let s = I18N[lang][el.dataset.i18n];
    if(s===undefined || typeof s !== 'string') return;
    s = s.replace('{count}', ONLINE_COUNT).replace('{year}', new Date().getFullYear());
    el.textContent = s;
  });
  try{ localStorage.setItem('kremera_lang', lang); }catch(e){}
  renderSheet(lang);
}
// ornamentos de canto das molduras
const CORNER = '<svg viewBox="0 0 28 28" fill="none" aria-hidden="true"><path d="M27 1.5 H10 M1.5 27 V10" stroke="currentColor" stroke-width="1.4"/><path d="M5.5 2.2 L8.8 5.5 L5.5 8.8 L2.2 5.5 Z" fill="currentColor"/></svg>';
document.querySelectorAll('.frame').forEach(f=>{
  for(let i=1;i<=4;i++){ const s=document.createElement('span'); s.className='corner c'+i; s.innerHTML=CORNER; f.appendChild(s); }
});
let _lang='pt';
try{ _lang = localStorage.getItem('kremera_lang') || 'pt'; }catch(e){}
setLang(_lang);
</script>
</body>
</html>
