# Kremera — customizações do MyAAC (versionadas)

Este fork do MyAAC roda o site do **Kremera** (Canary/crystalserver, client 15.24).
Aqui ficam as customizações que **não vivem em arquivo** do MyAAC, pra serem rastreadas no git.

## O que é versionado onde

| Customização | Onde mora | No git? |
|---|---|---|
| Logo + tema dark/dourado | `templates/kathrine/images/logo.png`, `templates/kathrine/style.css` | ✅ (arquivos) |
| Settings (template, status, classless, país) | DB `myaac_settings` | ✅ via `kremera/seed.sql` |
| Menus do site | DB `myaac_menu` | ✅ via `kremera/seed.sql` |
| Páginas custom (rules, downloads) | DB `myaac_pages` | ✅ via `kremera/seed.sql` |
| Notícia de boas-vindas | DB `myaac_news` | ✅ via `kremera/seed.sql` |
| Nome do site = "Kremera" | `config.local.php` (gitignored) | ⚠️ manual (abaixo) |

## Aplicar o seed (DB limpo ou pra ressincronizar)

```bash
cd ~/Projects/Kremera/myaac
docker exec -i crystalserver-database-1 \
  mariadb --default-character-set=utf8mb4 -ucrystalserver -p<SENHA_DB> \
  crystalserver < kremera/seed.sql
# depois limpe o cache do MyAAC (prefixo myaac_kremera*, NÃO *.cache):
docker exec myaac sh -c 'find /var/www/html/system/cache -type f ! -name index.html -delete'
```

O seed é **idempotente** (cada seção apaga as próprias linhas antes de inserir).
`--default-character-set=utf8mb4` é obrigatório (acentos/emoji).

## config.local.php (não versionado)

`config.local.php` é gitignored (tem credenciais). Além das chaves de instalação/DB,
ele precisa desta linha pra o site se chamar "Kremera" **sem** mexer no `serverName` do
`config.lua` do jogo (que fica "crystalserver"):

```php
$config['lua']['serverName'] = 'Kremera';
```

Funciona porque `load_config_lua()` faz `array_merge(arquivo, $config['lua'])` — o valor
setado em `config.local.php` (antes do parse) sobrescreve o lido do `config.lua`.

## Workflow ao mexer no MyAAC

1. Alterou **arquivo** (template/css/php/plugin)? Commit normal.
2. Alterou **DB** (setting/menu/página/notícia via admin ou SQL)? **Atualize `kremera/seed.sql`**
   pra refletir e commite junto — assim o git continua sendo a fonte da verdade.

Regenerar o seed a partir do DB atual (dados das 5 tabelas; elas só têm dados do Kremera):

```bash
docker exec crystalserver-database-1 mariadb-dump --default-character-set=utf8mb4 \
  -ucrystalserver -p<SENHA_DB> --no-create-info --skip-extended-insert --complete-insert \
  --no-tablespaces crystalserver \
  myaac_settings myaac_menu myaac_pages myaac_news myaac_news_categories
```

(reaplique os DELETEs/cabeçalho do seed por cima do dump pra manter a idempotência.)

## Notas

- Template travado no **kathrine** (`template_allow_change=false`) — pra reativar o seletor
  de temas, mude pra `true`.
- Criação de char é **classless**: `character_samples="0=Rook Sample"` (1 sample) esconde o
  seletor de vocação e cria todo char como **None**. Ver memória do projeto.
- Fork: **`ReeCrema/KremeraSite`** (de slawkens/myaac). Remotes: `kremera` → o fork
  (`git push kremera kremera`), `origin` → upstream slawkens/myaac (pull de updates). Branch: `kremera`.
