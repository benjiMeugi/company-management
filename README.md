# Projet Syteme d'information
Projet de système d'information développé autour de la gestion d'une entreprise.

L'application est basée sur une architecture microservices, dans laquelle les différents composants sont développés et déployés de manière indépendante. Certains services peuvent néanmoins communiquer entre eux pour assurer les différentes fonctionnalités du système.

## GIT
git submodule add https://github.com/benjiMeugi/auth-user-manager.git backends/authentication-api

## nginx
Serveur d'application principal

## mysql
SGBD

## backend
- API d'auhtenfification
- Gestion des ressource humaines

## frontend

## commandes utiles
docker exec -it authentication-api sh
docker exec -it employe-api sh