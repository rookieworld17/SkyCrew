# Craft CMS Projekt (ddev)

Dieses Projekt basiert auf **Craft CMS** und verwendet **ddev** als lokale Entwicklungsumgebung.  
Das Repository ist so vorbereitet, dass das Projekt von Grund auf lokal gestartet werden kann.

---

## Voraussetzungen

Bitte stelle sicher, dass folgende Software installiert ist:

- Docker
- ddev
- Git

---

## Projekt installieren

```bash
git clone https://github.com/username/project.git
cd project

cp .env.example .env
ddev start
ddev composer install
ddev npm install
```

## Projekt installieren

```bash
ddev import-db --file=done-skycrew-backup--2026-02-04.sql
```

## Apply Project Config

```bash
ddev craft project-config/apply
```

## Open site
```bash
ddev launch
```