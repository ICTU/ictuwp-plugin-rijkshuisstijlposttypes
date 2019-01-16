# Digitale-Overheid - WordPress: register Custom Post Types & Taxonomies
Register Custom Post Types and Custom Taxonomies.

Deze plugin registreert de custom posttypes en taxonomieën voor wp-rijkshuisstijl. 

Aanleiding voor het scheiden van theme en definitie van CPT / CT is het opzetten van aparte widget areas via de 'genesis-simple-sidebars' plugin. Deze hookt in via 'plugins_loaded' en dat is eerder dan de 'init' waarop het theme hookt. Daarnaast maakt deze verplaatsing het filteren van de searchwp zoekresultaten ook makkelijker.

## Geldig voor

### Custom Post Types:

* document, gedefinieerd via RHSWP_CPT_DOCUMENT
* slidertje, gedefinieerd via RHSWP_CPT_SLIDER

### Custom Taxonomies:

* dossier, gedefinieerd via RHSWP_CT_DOSSIER
* digitaleagenda, gedefinieerd via RHSWP_CT_DIGIBETER

## Contact
* Pim Nieuwenburg
* Paul van Buuren: paul@wbvb.nl

## To do:
* Maak slug voor CPT / CT ook vertaalbaar

## Current version:
* 2.0.1 - Added translation file. Default language now English.

## Version history
* 2.0.1 - Added translation file. Default language now English.
* 1.0.1 - More code transfer from theme files.
* 1.0.0 - First set up of plugin files.
