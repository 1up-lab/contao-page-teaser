Contao Page Teaser
==========================

Page teaser functionality for Contao Open Source CMS.

[![Author](http://img.shields.io/badge/author-@1upgmbh-blue.svg?style=flat-square)](https://1up.io)
[![Software License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](http://img.shields.io/packagist/dt/oneup/contao-page-teaser.svg?style=flat-square)](https://packagist.org/packages/oneup/contao-page-teaser)

### TL;DR
Features included:
* Page teaser as a content element.
* Page teaser as a module.

### Requirements
* PHP `^8.4`
* Contao Open Source CMS `^5.3`

### Installation
```
composer require oneup/contao-page-teaser
```

### About this extension
Adds page teaser functionality to Contao CMS as both a content element and a front-end module.

#### Page settings
Every page gets a new "Page Teaser" section (added right after the meta/SERP preview fields) with:
- Preview Text - rich text field (TinyMCE), supports insert tags, indexed for search
- Optional teaser image - toggle checkbox that reveals a single image with image size, alt text and title

These fields are also available on forward and redirect pages.

#### Content Element
A "Page Teaser" content element lets you manually pick one or more pages via the page tree (with drag-and-drop ordering) and renders each as a teaser (title, preview text, image) using a selectable output template.

#### Module - two modes
1. Individual: same manual page-picker as the content element (pick + order specific pages).
2. Automatic/navigation-based: builds the teaser list from the page tree itself, using the standard Contao navigation parameters (level offset, show level, hard limit, show protected/hidden pages, define root page) — so it behaves like a navigation module but renders teaser cards instead of links.

Both modes let you choose the output template (default: teasers_list) and optionally hide protected pages.

### License
MIT
