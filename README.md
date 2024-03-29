# Hämta lediga jobb från Varbi

## Hur man använder Region Hallands plugin "region-halland-varbi-works"

Nedan följer instruktioner hur du kan använda pluginet "region-halland-varbi-works".


## Användningsområde

Denna plugin skapar funktionalitet för att hämta lediga jobb från Varbi

```sh
Notera att denna plugin innehåller bara grundfunktionalitet utan tillhörande token-nycklar
```

## Licensmodell

Denna plugin använder licensmodell GPL-3.0. Du kan läsa mer om denna licensmodell via den medföljande filen:
```sh
LICENSE (https://github.com/RegionHalland/region-halland-varbi-works/blob/master/LICENSE)
```


## Installation och aktivering

```sh
A) Hämta pluginen via Git eller läs in det med Composer
B) Installera Region Hallands plugin i Wordpress plugin folder
C) Aktivera pluginet inifrån Wordpress admin
```


## Hämta hem pluginet via Git

```sh
git clone https://github.com/RegionHalland/region-halland-varbi-works.git
```


## Läs in pluginen via composer

Dessa två delar behöver du lägga in i din composer-fil

Repositories = var pluginen är lagrad, i detta fall på github

```sh
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/RegionHalland/region-halland-varbi-works.git"
  },
],
```
Require = anger vilken version av pluginen du vill använda, i detta fall version 1.0.0

OBS! Justera så att du hämtar aktuell version.

```sh
"require": {
  "regionhalland/region-halland-varbi-works": "1.0.0"
},
```


## Versionhistorik

### 1.6.0
- Uppdaterat med information om licensmodell
- Bifogat fil med licensmodell

### 1.5.0
- Justerat om alla listor edter publicerat datum, nyast först

### 1.4.0
- Lagt till filtrering av poster
- Lagt till kontakt + facklig representat på en enskild sida

### 1.3.1
- Lagt till fält som fattades

### 1.3.0
- Tagit bort post_type, använder en template istället

### 1.2.2
- Justerat fel variabler vid uppdaterad fil

### 1.2.1
- Justerat så att endast externa jobb visas

### 1.2.0
- Ändrat sökväg till datafiler
- Lagt till footer i enskilt jobb

### 1.1.0
- Lagt till datafiler

### 1.0.0
- Första version
