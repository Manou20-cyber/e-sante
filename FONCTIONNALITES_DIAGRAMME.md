# Plateforme de Suivi Médical Optique — e-santé
> Fonctionnalités & Diagramme de Classes complet

---

## 1. Fonctionnalités du Projet

### 1.1 Fonctionnalités — Patients

| # | Fonctionnalité | Description | Route(s) |
|---|---|---|---|
| F01 | Gestion du profil | Créer et gérer son profil pour accéder aux données personnelles médicales (nom, email, téléphone, adresse, date de naissance, sexe, n° sécurité sociale, médecin traitant). | `GET/PATCH/DELETE /profile` |
| F02 | Prise de rendez-vous | Réserver un créneau dans un cabinet optique (consultation, bilan oculaire, etc.) en choisissant un opticien et un créneau disponible. | `GET /patient/cabinets`, `POST /patient/cabinets/{cabinet}/opticiens/{opticien}/reserver`, `GET/POST/PUT/DELETE /patient/rendezvous` |
| F03 | Notifications | Recevoir des rappels de rendez-vous, prescriptions et résultats par notifications in-app (SMS/e-mail extensible). | `GET /patient/notifications`, `POST /patient/notifications/{id}/lu`, `POST /patient/notifications/tout-lire` |
| F04 | Documents médicaux | Télécharger et consulter ses propres documents médicaux (ordonnances, résultats, certificats, factures). | `GET /patient/documents`, `POST /patient/documents`, `GET /patient/documents/{document}/download` |
| F05 | Messagerie | Contacter un cabinet pour des questions médicales ou administratives via une messagerie intégrée. | `GET /patient/messages`, `GET /patient/messages/{cabinet:uuid}`, `POST /patient/messages`, `POST /patient/messages/{cabinet:uuid}/repondre` |
| F06 | Commandes de lunettes | Passer une commande de lunettes prescrites directement via la plateforme, avec sélection de produits du cabinet. | `GET /patient/commandes`, `GET /patient/commandes/create`, `POST /patient/commandes` |
| F07 | Retours de commande | Initier un retour en cas de non-satisfaction d'un produit commandé. | `POST /patient/commandes/{commande}/retour` |
| F08 | Historique | Visualiser l'historique complet : rendez-vous, ordonnances, commandes, factures (passés/à venir). | `GET /patient/historique` |
| F09 | Dossier médical | Consulter et mettre à jour son dossier médical : antécédents, allergies, traitements en cours. | `GET /patient/dossier`, `PUT /patient/dossier` |
| F10 | Factures & Paiements | Consulter et payer ses factures en ligne (carte, espèces, virement, chèque, mutuelle, mobile money). | `GET /patient/factures`, `GET /patient/factures/{facture}`, `POST /patient/factures/{facture}/payer` |

---

### 1.2 Fonctionnalités — Cabinets Optiques (Opticien + Admin Cabinet)

| # | Fonctionnalité | Description | Route(s) |
|---|---|---|---|
| F11 | Gestion des rendez-vous | Définir créneaux horaires, durée, type et limites de consultations. Confirmer, annuler, gérer les rendez-vous patients. | `GET/POST/PUT/DELETE /dashboard/rendezvous`, `GET /dashboard/planning`, `POST/PUT/DELETE /dashboard/planning` |
| F12 | Gestion du stock | Gérer le stock de montures, lentilles, verres et accessoires (libellé, référence, prix, seuil d'alerte, marque, images). | `GET/POST/PUT/DELETE /dashboard/produits` |
| F13 | Statistiques & rapports | Générer des rapports d'activité et des tableaux de bord statistiques. | `GET /dashboard/statistiques` |
| F14 | Résultats d'examens | Enregistrer et stocker les résultats des examens optiques des patients (acuité OD/OG, tension oculaire, résultats complémentaires). | `GET/POST/PUT/DELETE /dashboard/examens` |
| F15 | Consultations & prescriptions | Gérer les consultations et prescriptions optiques/ophtalmologiques (diagnostic, notes, montant). | `GET/POST/PUT/DELETE /dashboard/consultations` |
| F16 | Paiements & facturation | Suivre les paiements, émettre et archiver les factures (HT, TVA, TTC, PDF). | Inclus dans la gestion commandes/consultations |
| F17 | Garanties & retours | Gérer les garanties produits et traiter les demandes de retour des patients. | Intégré commandes admin |
| F18 | Dossiers médicaux | Administrer les dossiers médicaux des patients : actes, certificats, antécédents, ordonnances. | `GET /dashboard/dossiers`, `GET /dashboard/dossiers/{patient}`, `POST /dashboard/dossiers/{patient}/ordonnances` |
| F19 | Messagerie admin | Répondre aux messages des patients depuis l'interface cabinet. | `GET /dashboard/messages`, `GET /dashboard/messages/{interlocuteur}`, `POST /dashboard/messages/{interlocuteur}/repondre` |
| F20 | Gestion du staff | Gérer les opticiens du cabinet (création, affectation, droits). | `GET/POST/PUT/DELETE /dashboard/opticiens` *(cabinet_admin uniquement)* |

---

### 1.3 Fonctionnalités — Administrateur du Système

| # | Fonctionnalité | Description | Route(s) |
|---|---|---|---|
| F21 | Gestion des cabinets | Créer des cabinets sur la plateforme, leur attribuer un administrateur, valider/désactiver les cabinets. | `GET/POST/PUT/DELETE /dashboard/cabinets`, `PATCH /dashboard/cabinets/{cabinet}/valider` |
| F22 | Droits d'accès | Définir les rôles et niveaux d'accès de chaque utilisateur via le système de permissions (Spatie). | `GET/POST/PUT/DELETE /dashboard/users` |
| F23 | Paramétrage | Configurer les types de consultation, langues et autres paramètres globaux de la plateforme. | `GET/POST/PUT/DELETE /dashboard/parametres` |
| F24 | Surveillance & audit | Surveiller les activités sur la plateforme et générer des rapports d'audit (logs IP, user-agent, actions). | Logs `audit_logs`, `GET /dashboard/statistiques` |
| F25 | Gestion des patients | Consulter et gérer les comptes patients de la plateforme. | `GET/POST/PUT/DELETE /dashboard/patients` |

---

### 1.4 Exigences Non-Fonctionnelles

| Critère | Exigence | Implémentation |
|---|---|---|
| Accessibilité | Conformité WCAG — utilisateurs malvoyants et personnes âgées inclus. | Interface Tailwind CSS v4, navigation claire |
| Sécurité | Chiffrement des données, authentification sécurisée, sauvegardes régulières. | Laravel Breeze, Spatie Permission, audit_logs, hashing Bcrypt |
| Performance | Temps de chargement optimisés, mise en cache, gestion de la charge. | Cache Laravel, sessions optimisées |
| Convivialité | Interface intuitive, tests d'utilisabilité, navigation claire. | Laravel Breeze + Tailwind CSS v4 |
| Scalabilité | Architecture cloud évolutive (horizontale/verticale) selon la demande. | Laravel Cloud (déploiement cible) |

---

## 2. Diagramme de Classes — Schéma Complet des Tables

### 2.1 Légende

| Notation | Signification |
|---|---|
| `PK` | Clé primaire (Primary Key) |
| `FK` | Clé étrangère (Foreign Key) |
| `UK` | Contrainte d'unicité |
| `→` | Association dirigée (FK vers entité cible) |
| `1 *` | Association un-à-plusieurs |
| `1 1` | Association un-à-un |
| `* *` | Association plusieurs-à-plusieurs (table pivot) |
| `enum(...)` | Valeurs énumérées |
| `nullable` | Champ optionnel |

---

### 2.2 Entités Principales

```
┌─────────────────────────────────────────────────────────────┐
│                           USERS                             │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + name            : string                                  │
│ + email           [UK] : string                             │
│ + email_verified_at : timestamp | nullable                  │
│ + password        : string (hashed)                         │
│ + phone           : string(20) | nullable                   │
│ + cabinet_id      [FK→cabinets_optiques] : bigint | nullable│
│ + remember_token  : string | nullable                       │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  hasOne → patients                                          │
│  hasOne → cabinets_optiques (admin)                         │
│  belongsTo → cabinets_optiques (opticien)                  │
│  hasMany → creneaux_horaires (opticien_id)                  │
│  hasMany → messages (expediteur_id / destinataire_id)       │
│  hasMany → audit_logs                                       │
│  hasMany → rendezvous (via medecin_id dans consultations)   │
│ Rôles : super_admin | cabinet_admin | opticien | patient    │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                          PATIENTS                           │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + user_id         [FK→users] : bigint                       │
│ + date_naissance  : date | nullable                         │
│ + sexe            : enum('M','F','autre') | nullable        │
│ + adresse         : text | nullable                         │
│ + ville           : string(100) | nullable                  │
│ + code_postal     : string(10) | nullable                   │
│ + numero_securite_sociale : string(25) | nullable           │
│ + medecin_traitant : string | nullable                      │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → users                                          │
│  hasOne → dossiers_medicaux                                 │
│  hasMany → rendezvous                                       │
│  hasMany → consultations                                    │
│  hasMany → commandes                                        │
│  hasMany → documents_medicaux                               │
│  hasMany → factures                                         │
│  hasMany → retours_commande                                 │
│  hasMany → examens_optiques                                 │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                     CABINETS_OPTIQUES                       │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + uuid            [UK] : uuid (auto-généré)                 │
│ + user_id         [FK→users] : bigint (admin cabinet)       │
│ + nom             : string                                  │
│ + adresse         : text                                    │
│ + ville           : string(100)                             │
│ + code_postal     : string(10)                              │
│ + telephone       : string(20)                              │
│ + email           : string | nullable                       │
│ + siret           : string(20) | nullable                   │
│ + description     : text | nullable                         │
│ + logo            : string | nullable                       │
│ + est_actif       : boolean (default: true)                 │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → users (admin)                                  │
│  hasMany → users (opticiens via cabinet_id)                 │
│  hasMany → creneaux_horaires                                │
│  hasMany → rendezvous                                       │
│  hasMany → produits                                         │
│  hasMany → consultations                                    │
│  hasMany → commandes                                        │
│  hasMany → factures                                         │
│  hasMany → messages                                         │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                     CRENEAUX_HORAIRES                       │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + cabinet_id      [FK→cabinets_optiques] : bigint           │
│ + opticien_id     [FK→users] : bigint | nullable            │
│ + jour_semaine    : tinyint (1=Lundi … 7=Dimanche)         │
│ + heure_debut     : time                                    │
│ + heure_fin       : time                                    │
│ + duree_consultation : smallint (min, default: 30)         │
│ + capacite_max    : smallint (default: 1)                   │
│ + prix            : decimal(10,0) | nullable (XAF)          │
│ + est_actif       : boolean (default: true)                 │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → cabinets_optiques                              │
│  belongsTo → users (opticien)                               │
│  hasMany → rendezvous                                       │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                         RENDEZVOUS                          │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + patient_id      [FK→patients] : bigint                    │
│ + cabinet_id      [FK→cabinets_optiques] : bigint           │
│ + creneau_id      [FK→creneaux_horaires] : bigint | nullable│
│ + opticien_id     [FK→users] : bigint | nullable            │
│ + date            : datetime                                │
│ + duree           : smallint (min, default: 30)             │
│ + type            : string(50) (default: 'consultation')    │
│ + statut          : enum('en_attente','confirme','annule',  │
│                         'termine','absent')                 │
│ + motif           : text | nullable                         │
│ + notes           : text | nullable                         │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → patients                                       │
│  belongsTo → cabinets_optiques                              │
│  belongsTo → creneaux_horaires                              │
│  belongsTo → users (opticien)                               │
│  hasOne → consultations                                     │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                       DOSSIERS_MEDICAUX                     │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + patient_id      [FK→patients] : bigint                    │
│ + antecedents     : text | nullable                         │
│ + allergies       : text | nullable                         │
│ + traitements_en_cours : text | nullable                    │
│ + notes           : text | nullable                         │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → patients                                       │
│  hasMany → ordonnances                                      │
│  hasMany → documents_medicaux                               │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                        CONSULTATIONS                        │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + rendezvous_id   [FK→rendezvous] : bigint | nullable       │
│ + patient_id      [FK→patients] : bigint                    │
│ + cabinet_id      [FK→cabinets_optiques] : bigint           │
│ + medecin_id      [FK→users] : bigint (opticien)            │
│ + date            : datetime                                │
│ + type            : string(50) (default: 'bilan_visuel')    │
│ + diagnostic      : text | nullable                         │
│ + notes           : text | nullable                         │
│ + montant         : decimal(10,2) | nullable                │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → rendezvous                                     │
│  belongsTo → patients                                       │
│  belongsTo → cabinets_optiques                              │
│  belongsTo → users (medecin)                                │
│  hasOne → examens_optiques                                  │
│  hasMany → ordonnances                                      │
│  hasMany → factures                                         │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                      EXAMENS_OPTIQUES                       │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + consultation_id [FK→consultations] : bigint               │
│ + patient_id      [FK→patients] : bigint                    │
│ + acuite_od       : decimal(4,2) | nullable (sans correction│
│ + acuite_og       : decimal(4,2) | nullable                 │
│ + acuite_od_corrigee : decimal(4,2) | nullable              │
│ + acuite_og_corrigee : decimal(4,2) | nullable              │
│ + tension_od      : decimal(5,2) | nullable (mmHg)          │
│ + tension_og      : decimal(5,2) | nullable                 │
│ + resultats_complementaires : json | nullable               │
│ + observations    : text | nullable                         │
│ + chemin_fichier  : string | nullable                       │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → consultations                                  │
│  belongsTo → patients                                       │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                        ORDONNANCES                          │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + dossier_id      [FK→dossiers_medicaux] : bigint           │
│ + consultation_id [FK→consultations] : bigint | nullable    │
│ + date            : date                                    │
│ + sphere_od       : decimal(5,2) | nullable (oeil droit)    │
│ + sphere_og       : decimal(5,2) | nullable (oeil gauche)   │
│ + cylindre_od     : decimal(5,2) | nullable                 │
│ + cylindre_og     : decimal(5,2) | nullable                 │
│ + axe_od          : decimal(5,2) | nullable                 │
│ + axe_og          : decimal(5,2) | nullable                 │
│ + addition_od     : decimal(5,2) | nullable                 │
│ + addition_og     : decimal(5,2) | nullable                 │
│ + ecart_pupillaire : decimal(5,2) | nullable (mm)           │
│ + notes           : text | nullable                         │
│ + chemin_pdf      : string | nullable                       │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → dossiers_medicaux                              │
│  belongsTo → consultations                                  │
│  hasMany → commandes                                        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                          PRODUITS                           │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + cabinet_id      [FK→cabinets_optiques] : bigint           │
│ + libelle         : string                                  │
│ + description     : text | nullable                         │
│ + reference       : string(50) | nullable                   │
│ + prix            : decimal(10,2)                           │
│ + stock           : unsignedInt (default: 0)                │
│ + stock_alerte    : unsignedInt (default: 5)                │
│ + categorie       : enum('monture','lentille','verre',      │
│                         'accessoire','autre')               │
│ + marque          : string(100) | nullable                  │
│ + images          : json | nullable                         │
│ + est_actif       : boolean (default: true)                 │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → cabinets_optiques                              │
│  belongsToMany → commandes (via commande_produit)           │
│  hasMany → garanties                                        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                         COMMANDES                           │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + patient_id      [FK→patients] : bigint                    │
│ + cabinet_id      [FK→cabinets_optiques] : bigint           │
│ + ordonnance_id   [FK→ordonnances] : bigint | nullable      │
│ + numero          [UK] : string (auto-généré)               │
│ + statut          : enum('en_attente','confirmee',          │
│                         'en_preparation','prete',           │
│                         'livree','annulee')                 │
│ + montant_total   : decimal(10,2) (default: 0)              │
│ + adresse_livraison : text | nullable                       │
│ + notes           : text | nullable                         │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → patients                                       │
│  belongsTo → cabinets_optiques                              │
│  belongsTo → ordonnances                                    │
│  belongsToMany → produits (via commande_produit)            │
│  hasMany → factures                                         │
│  hasMany → garanties                                        │
│  hasMany → retours_commande                                 │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                     COMMANDE_PRODUIT (pivot)                │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + commande_id     [FK→commandes] : bigint                   │
│ + produit_id      [FK→produits] : bigint                    │
│ + quantite        : unsignedInt (default: 1)                │
│ + prix_unitaire   : decimal(10,2)                           │
│ + specifications  : text | nullable (personnalisation)      │
│ + created_at / updated_at : timestamps                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                      DOCUMENTS_MEDICAUX                     │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + patient_id      [FK→patients] : bigint                    │
│ + dossier_id      [FK→dossiers_medicaux] : bigint | nullable│
│ + uploaded_by     [FK→users] : bigint                       │
│ + nom             : string                                  │
│ + type            : enum('ordonnance','resultat',           │
│                         'certificat','facture','autre')     │
│ + chemin_fichier  : string                                  │
│ + mime_type       : string(100) | nullable                  │
│ + taille          : bigint | nullable (octets)              │
│ + description     : text | nullable                         │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → patients                                       │
│  belongsTo → dossiers_medicaux                              │
│  belongsTo → users (uploader)                               │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                          FACTURES                           │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + patient_id      [FK→patients] : bigint                    │
│ + cabinet_id      [FK→cabinets_optiques] : bigint           │
│ + commande_id     [FK→commandes] : bigint | nullable        │
│ + consultation_id [FK→consultations] : bigint | nullable    │
│ + numero          [UK] : string (auto-généré)               │
│ + montant_ht      : decimal(10,2) (default: 0)              │
│ + taux_tva        : decimal(5,2) (default: 20.00)           │
│ + montant_ttc     : decimal(10,2) (default: 0)              │
│ + statut          : enum('brouillon','emise','payee',       │
│                         'annulee','remboursee')             │
│ + date_emission   : date                                    │
│ + date_echeance   : date | nullable                         │
│ + chemin_pdf      : string | nullable                       │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → patients                                       │
│  belongsTo → cabinets_optiques                              │
│  belongsTo → commandes                                      │
│  belongsTo → consultations                                  │
│  hasMany → paiements                                        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                          PAIEMENTS                          │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + facture_id      [FK→factures] : bigint                    │
│ + montant         : decimal(10,2)                           │
│ + methode         : enum('carte','especes','virement',      │
│                         'cheque','mutuelle','mobile_money') │
│ + reference       : string(100) | nullable                  │
│ + date_paiement   : datetime                                │
│ + notes           : text | nullable                         │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → factures                                       │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                         MESSAGES                            │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + expediteur_id   [FK→users] : bigint                       │
│ + destinataire_id [FK→users] : bigint                       │
│ + cabinet_id      [FK→cabinets_optiques] : bigint | nullable│
│ + contenu         : text                                    │
│ + objet           : string(200) | nullable                  │
│ + lu_at           : timestamp | nullable                    │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Relations :                                                 │
│  belongsTo → users (expediteur)                             │
│  belongsTo → users (destinataire)                           │
│  belongsTo → cabinets_optiques                              │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                       GARANTIES                             │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + commande_id     [FK→commandes] : bigint                   │
│ + produit_id      [FK→produits] : bigint                    │
│ + date_debut      : date                                    │
│ + date_fin        : date                                    │
│ + description     : text | nullable                         │
│ + est_active      : boolean (default: true)                 │
│ + created_at / updated_at : timestamps                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                     RETOURS_COMMANDE                        │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + commande_id     [FK→commandes] : bigint                   │
│ + patient_id      [FK→patients] : bigint                    │
│ + raison          : text                                    │
│ + statut          : enum('en_attente','approuve',           │
│                         'refuse','traite')                  │
│ + montant_rembourse : decimal(10,2) | nullable              │
│ + notes_cabinet   : text | nullable                         │
│ + created_at / updated_at : timestamps                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                        PARAMETRES                           │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + cle             [UK] : string(100)                        │
│ + valeur          : text | nullable                         │
│ + groupe          : string(50) (default: 'general')         │
│ + description     : text | nullable                         │
│ + est_public      : boolean (default: false)                │
│ + created_at / updated_at : timestamps                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                        AUDIT_LOGS                           │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : bigint                             │
│ + user_id         [FK→users] : bigint | nullable            │
│ + action          : string(100)                             │
│ + entite_type     : string(100) | nullable                  │
│ + entite_id       : bigint | nullable                       │
│ + donnees_avant   : json | nullable                         │
│ + donnees_apres   : json | nullable                         │
│ + ip_address      : string(45) | nullable                   │
│ + user_agent      : string | nullable                       │
│ + created_at / updated_at : timestamps                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                       NOTIFICATIONS                         │
├─────────────────────────────────────────────────────────────┤
│ + id              [PK] : uuid                               │
│ + type            : string (classe notification Laravel)    │
│ + notifiable_type : string (polymorphique)                  │
│ + notifiable_id   : bigint (polymorphique)                  │
│ + data            : text (JSON payload)                     │
│ + read_at         : timestamp | nullable                    │
│ + created_at / updated_at : timestamps                      │
└─────────────────────────────────────────────────────────────┘
```

---

### 2.3 Système de Rôles & Permissions (Spatie Permission)

```
┌─────────────────────────────────────────────────────────────┐
│                         ROLES                               │
├─────────────────────────────────────────────────────────────┤
│ + id          [PK] : bigint                                 │
│ + name        [UK] : string                                 │
│ + guard_name  : string (default: 'web')                     │
│ + created_at / updated_at : timestamps                      │
├─────────────────────────────────────────────────────────────┤
│ Valeurs :                                                   │
│  • super_admin   — tous les droits système                  │
│  • cabinet_admin — gestion cabinet + staff                  │
│  • opticien      — soins & consultations cabinet            │
│  • patient       — actions patient                          │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                       PERMISSIONS                           │
├─────────────────────────────────────────────────────────────┤
│ + id          [PK] : bigint                                 │
│ + name        [UK] : string                                 │
│ + guard_name  : string (default: 'web')                     │
│ + created_at / updated_at : timestamps                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│               MODEL_HAS_ROLES (pivot)                       │
├─────────────────────────────────────────────────────────────┤
│ + role_id     [FK→roles] : bigint                           │
│ + model_type  : string ('App\Models\User')                  │
│ + model_id    [FK→users] : bigint                           │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│             MODEL_HAS_PERMISSIONS (pivot)                   │
├─────────────────────────────────────────────────────────────┤
│ + permission_id [FK→permissions] : bigint                   │
│ + model_type  : string                                      │
│ + model_id    : bigint                                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│              ROLE_HAS_PERMISSIONS (pivot)                   │
├─────────────────────────────────────────────────────────────┤
│ + permission_id [FK→permissions] : bigint                   │
│ + role_id     [FK→roles] : bigint                           │
└─────────────────────────────────────────────────────────────┘
```

---

### 2.4 Matrice Rôles ↔ Permissions

| Permission | patient | opticien | cabinet_admin | super_admin |
|---|:---:|:---:|:---:|:---:|
| `patient.profile.view` | ✅ | | | ✅ |
| `patient.profile.edit` | ✅ | | | ✅ |
| `patient.rendezvous.create` | ✅ | | | ✅ |
| `patient.rendezvous.view` | ✅ | | | ✅ |
| `patient.rendezvous.cancel` | ✅ | | | ✅ |
| `patient.dossier.view` | ✅ | | | ✅ |
| `patient.documents.view` | ✅ | | | ✅ |
| `patient.documents.download` | ✅ | | | ✅ |
| `patient.messages.send` | ✅ | | | ✅ |
| `patient.messages.view` | ✅ | | | ✅ |
| `patient.commandes.create` | ✅ | | | ✅ |
| `patient.commandes.view` | ✅ | | | ✅ |
| `patient.commandes.return` | ✅ | | | ✅ |
| `patient.historique.view` | ✅ | | | ✅ |
| `cabinet.rendezvous.manage` | | ✅ | ✅ | ✅ |
| `cabinet.creneaux.manage` | | ✅ | ✅ | ✅ |
| `cabinet.stock.manage` | | ✅ | ✅ | ✅ |
| `cabinet.consultations.manage` | | ✅ | ✅ | ✅ |
| `cabinet.examens.manage` | | ✅ | ✅ | ✅ |
| `cabinet.ordonnances.manage` | | ✅ | ✅ | ✅ |
| `cabinet.dossiers.manage` | | ✅ | ✅ | ✅ |
| `cabinet.factures.manage` | | ✅ | ✅ | ✅ |
| `cabinet.paiements.manage` | | ✅ | ✅ | ✅ |
| `cabinet.garanties.manage` | | ✅ | ✅ | ✅ |
| `cabinet.retours.manage` | | ✅ | ✅ | ✅ |
| `cabinet.messages.manage` | | ✅ | ✅ | ✅ |
| `cabinet.rapports.view` | | ✅ | ✅ | ✅ |
| `cabinet.staff.manage` | | | ✅ | ✅ |
| `admin.cabinets.manage` | | | | ✅ |
| `admin.users.manage` | | | | ✅ |
| `admin.roles.manage` | | | | ✅ |
| `admin.parametres.manage` | | | | ✅ |
| `admin.audit.view` | | | | ✅ |
| `admin.rapports.view` | | | | ✅ |

---

### 2.5 Tables d'Infrastructure Laravel

```
┌─────────────────────────────────────────────────────────────┐
│               PASSWORD_RESET_TOKENS                         │
├─────────────────────────────────────────────────────────────┤
│ + email   [PK] : string                                     │
│ + token   : string                                          │
│ + created_at : timestamp | nullable                         │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                         SESSIONS                            │
├─────────────────────────────────────────────────────────────┤
│ + id          [PK] : string                                 │
│ + user_id     [FK→users] : bigint | nullable                │
│ + ip_address  : string(45) | nullable                       │
│ + user_agent  : text | nullable                             │
│ + payload     : longtext                                    │
│ + last_activity : int                                       │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                           CACHE                             │
├─────────────────────────────────────────────────────────────┤
│ + key         [PK] : string                                 │
│ + value       : mediumtext                                  │
│ + expiration  : int                                         │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                           JOBS                              │
├─────────────────────────────────────────────────────────────┤
│ + id          [PK] : bigint                                 │
│ + queue       : string                                      │
│ + payload     : longtext                                    │
│ + attempts    : tinyint                                     │
│ + reserved_at : int | nullable                              │
│ + available_at : int                                        │
│ + created_at  : int                                         │
└─────────────────────────────────────────────────────────────┘
```

---

### 2.6 Vue d'Ensemble des Relations

```
users ──────────────────────────────────────────────────────────┐
  │ 1                                                            │
  │ hasOne                                                       │
  ↓ *                                                            │
patients ──────────────────────────────────────────────────────┐│
  │ hasOne → dossiers_medicaux                                  ││
  │ hasMany → rendezvous                                        ││
  │ hasMany → consultations                                     ││
  │ hasMany → commandes                                         ││
  │ hasMany → documents_medicaux                                ││
  │ hasMany → factures                                          ││
  │ hasMany → examens_optiques                                  ││
  │ hasMany → retours_commande                                  ││
  ↓                                                             ││
dossiers_medicaux                                               ││
  │ hasMany → ordonnances                                       ││
  │ hasMany → documents_medicaux                                ││
                                                                ││
users ─────────────────────────────────────────────────────────┘│
  │ hasOne → cabinets_optiques (admin)                          │
  │ belongsTo → cabinets_optiques (opticien via cabinet_id)     │
  │ hasMany → creneaux_horaires (opticien_id)                   │
  ↓                                                             │
cabinets_optiques ─────────────────────────────────────────────┘
  │ hasMany → creneaux_horaires
  │ hasMany → rendezvous
  │ hasMany → produits
  │ hasMany → consultations
  │ hasMany → commandes
  │ hasMany → factures
  │ hasMany → messages
  ↓
creneaux_horaires → rendezvous → consultations → examens_optiques
                                              └→ ordonnances
commandes ←──────── produits (via commande_produit)
  │ hasMany → garanties
  │ hasMany → retours_commande
  │ hasMany → factures
factures → paiements
messages (users ←→ users via cabinet)
audit_logs ← users
notifications (morphTo → users)
```

---

## 3. Résumé des Tables (26 tables au total)

| # | Table | Rôle |
|---|---|---|
| 1 | `users` | Compte utilisateur (tous rôles) |
| 2 | `patients` | Profil médical patient |
| 3 | `cabinets_optiques` | Cabinet optique (entité métier) |
| 4 | `creneaux_horaires` | Disponibilités opticien |
| 5 | `rendezvous` | Réservations patient-cabinet |
| 6 | `dossiers_medicaux` | Dossier médical patient |
| 7 | `consultations` | Actes médicaux réalisés |
| 8 | `examens_optiques` | Résultats examens visuels |
| 9 | `ordonnances` | Prescriptions optiques |
| 10 | `produits` | Catalogue produits cabinet |
| 11 | `commandes` | Commandes de produits |
| 12 | `commande_produit` | Pivot commandes ↔ produits |
| 13 | `documents_medicaux` | Fichiers médicaux patients |
| 14 | `factures` | Facturation |
| 15 | `paiements` | Transactions de paiement |
| 16 | `messages` | Messagerie patient ↔ cabinet |
| 17 | `garanties` | Garanties produits |
| 18 | `retours_commande` | Retours & remboursements |
| 19 | `parametres` | Configuration plateforme |
| 20 | `audit_logs` | Journal d'activité sécurité |
| 21 | `notifications` | Notifications in-app |
| 22 | `roles` | Rôles Spatie Permission |
| 23 | `permissions` | Permissions Spatie Permission |
| 24 | `model_has_roles` | Pivot user ↔ rôle |
| 25 | `model_has_permissions` | Pivot user ↔ permission directe |
| 26 | `role_has_permissions` | Pivot rôle ↔ permission |
| — | `password_reset_tokens` | Reset mot de passe |
| — | `sessions` | Sessions web |
| — | `cache` | Cache applicatif |
| — | `jobs` | File d'attente Laravel |
