# Afin de récupérer le projet:

## Etape 1:
* git clone https://github.com/Manguet/Hackaton.git
* composer install
* yarn install

## Etape 2:
* Lancer yarn encore dev --watch
* Lancer symfony server:start

## Etape 3:
* Vérifier que tout s'affiche correctement.


# Fonctionnalités implantées:

* Bootstrap
* Formulaires Bootstrap
* Font Awesome free
* Un service de slugger que l'on peut implanter directement dans une méthode!

# Workflow Git:

## Pour commencer:
* git checkout dev
* git pull origin dev
* git checkout -b nom_de_la_branche_ou_lon_va_travailler
* travailler dessus

## Une fois la Feature terminée:
* git add nom_des_dossiers_a_add   -> Pas de add . si nous ne sommes pas sur
* git commit -m "message_du_commit"
* git pull origin dev
* Régler les conflis si il y en a
* git add les_fichiers_qui_ont_eu_les_conflits
* git commit -m "conflicts resolved"
* git push origin nom_de_la_branche_sur_laquelle_on_est
* Faire une pull request
* Prévenir qu'une pull request est en attente

Reprendre ensuite le Pour commencer
