# Système de Réclamations QR Code – Hôtel

## Aperçu
Système de gestion de réclamations par QR Code pour hôtels permettant aux clients de soumettre et suivre leurs réclamations concernant leur chambre via un QR code personnalisé.

## Architecture

### Backend
- Framework Laravel pour la gestion des APIs REST
- Base de données MySQL

### Frontend
- Angular pour une interface responsive
- Interface client mobile-friendly
- Dashboard sécurisé pour techniciens et administration

## Acteurs
- Réceptionniste : Génère et remet les QR codes aux clients
- Client : Scanne le QR code et soumet sa réclamation
- Agent de maintenance : Traite les tâches associées
- Chef de maintenance : Supervise, attribue et valide les interventions
- Administrateur : Supervise l'ensemble du système

## Fonctionnalités clés
- Génération de QR codes liés aux chambres
- Soumission et suivi des réclamations en temps réel
- Gestion des tâches de maintenance
- Suivi de la durée de vie des produits
- Statistiques et rapports 