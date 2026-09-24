<?php
/* ==========================================================================
   CONTENU DU SITE (textes et listes)
   --------------------------------------------------------------------------
   Pour modifier un chiffre, ajouter une actualité ou un témoignage,
   il suffit de modifier les tableaux ci-dessous : les pages (partials/,
   solution.php, actualite.php...) les affichent automatiquement.

   Les "slugs" (ex. 'travail-temporaire') servent à construire les adresses
   des pages : solution.php?s=travail-temporaire. Ils ne doivent contenir
   que des minuscules, des chiffres et des tirets, et être uniques.

   Textes longs (articles) : liste de blocs ['p', ...], ['h2', ...], ['ul', [...]]
   ========================================================================== */

/* --- Icônes SVG réutilisées (pour éviter de les recopier partout) --- */
$icones = [
    'equipe'     => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
    'cube'       => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>',
    'horloge'    => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
    'calendrier' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>',
    'document'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>',
    'puce'       => '<rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line>',
    'panier'     => '<circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>',
    'coche'      => '<polyline points="20 6 9 17 4 12"></polyline>',
    'lieu'       => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>',
    'telephone'  => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>',
    'email'      => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline>',
];

/* --- Lien Google Maps vers l'adresse de CAFPM (pied de page, page contact) --- */
const LIEN_GOOGLE_MAPS = 'https://www.google.com/maps/search/?api=1&query=Cocody+Riviera+Bonoumin%2C+Abidjan';

/* --- Horaires d'ouverture (page contact) : À CONFIRMER par CAFPM --- */
$horaires = [
    ['jours' => 'Du lundi au vendredi', 'heures' => '8h00 – 17h30'],
    ['jours' => 'Samedi',               'heures' => 'Sur rendez-vous'],
];
$horaires_a_confirmer = true; // Passer à false une fois les horaires validés

/* --- Chiffres clés affichés dans le Hero --- */
$statistiques = [
    ['icone' => 'equipe',  'valeur' => '+ 1 000', 'libelle' => 'Profils disponibles'],
    ['icone' => 'cube',    'valeur' => '+ 50',    'libelle' => 'Entreprises partenaires'],
    ['icone' => 'horloge', 'valeur' => '+ 10 ans', 'libelle' => "d'expérience"],
];

/* ==========================================================================
   SOLUTIONS
   - titre / texte / icone : carte de l'accueil
   - mise_en_avant         : icône sur fond jaune
   - le reste              : page de détail solution.php?s=<slug>
   - candidat => true      : la page propose aussi le tiroir "Créer mon profil"
   ========================================================================== */
$solutions = [
    [
        'slug'     => 'travail-temporaire',
        'icone'    => 'horloge',
        'titre'    => 'Travail temporaire',
        'texte'    => 'Une réponse rapide et flexible à vos besoins ponctuels ou saisonniers.',
        'accroche' => 'Renforcez vos équipes en quelques jours, pour la durée dont vous avez besoin.',
        'intro'    => [
            "Pic d'activité, remplacement d'un salarié absent, lancement d'un projet ou saison commerciale : certaines situations exigent du personnel supplémentaire, rapidement et pour une durée limitée. Le travail temporaire vous permet d'y répondre sans alourdir durablement vos effectifs permanents.",
            "CAFPM sélectionne, met en place et suit les intérimaires dont vous avez besoin. Nous restons leur employeur : contrat de mission, paie, déclarations et obligations sociales sont gérés par nos soins, dans le respect du Code du travail ivoirien.",
        ],
        'pour_qui' => [
            "Les entreprises qui font face à un surcroît d'activité ponctuel ou saisonnier",
            'Les services qui doivent remplacer un collaborateur absent (congé, maladie, formation)',
            'Les commerces qui préparent une période de forte affluence (fêtes, rentrée, promotions)',
            'Les organisations qui démarrent un projet ou un chantier à durée déterminée',
        ],
        'missions' => [
            'Analyse de votre besoin : poste, compétences, horaires, durée et lieu de la mission',
            'Recherche et présélection des profils dans notre vivier de candidats',
            'Entretiens, vérification des références et des qualifications',
            'Établissement des contrats et gestion administrative complète',
            'Paie des intérimaires et déclarations sociales',
            "Suivi de la mission et recherche d'un remplaçant en cas d'absence ou d'inadéquation",
        ],
        'etapes' => [
            ['Vous exprimez votre besoin', 'Via le formulaire « Déposer un besoin » ou par téléphone. Un conseiller vous recontacte sous 24 h pour préciser le poste et les conditions de la mission.'],
            ['Nous sélectionnons', 'Nous identifions et évaluons les candidats disponibles, puis vous présentons les profils retenus.'],
            ['La mission démarre', "L'intérimaire rejoint vos équipes. CAFPM se charge du contrat, de la paie et des formalités."],
            ['Nous assurons le suivi', 'Points réguliers avec vous et avec l’intérimaire, ajustements, prolongation ou fin de mission selon vos besoins.'],
        ],
        'avantages' => [
            ['Réactivité', "Du personnel opérationnel dans des délais courts, adaptés à l'urgence de votre situation."],
            ['Flexibilité', 'Vous ajustez vos effectifs à votre activité réelle, sans engagement durable.'],
            ['Administratif allégé', 'Contrats, paie et déclarations sont pris en charge par CAFPM.'],
            ['Conformité', 'Des missions encadrées dans le respect de la réglementation ivoirienne du travail.'],
        ],
        'faq' => [
            ['Dans quels délais pouvez-vous fournir du personnel ?', "Cela dépend du profil et du nombre de postes. Pour les métiers courants, nous pouvons souvent proposer des candidats en quelques jours. Indiquez-nous la date de démarrage souhaitée : nous vous confirmons un délai réaliste dès le premier échange."],
            ["Qui est l'employeur de l'intérimaire ?", "CAFPM. Nous signons le contrat de mission avec l'intérimaire et un contrat de prestation avec votre entreprise. Vous organisez le travail au quotidien, nous gérons l'administratif."],
            ["Comment est calculé le coût d'une mission ?", "Le tarif dépend du poste, de la durée, des horaires et du nombre de personnes. Nous vous adressons un devis détaillé après l'analyse de votre besoin, sans engagement."],
            ['Que se passe-t-il si le profil ne convient pas ?', 'Signalez-le à votre conseiller : nous faisons le point avec vous et recherchons une solution, y compris un remplacement si nécessaire.'],
        ],
        'cta_titre' => 'Besoin de renfort rapidement ?',
        'cta_texte' => 'Décrivez-nous votre besoin : un conseiller CAFPM vous recontacte sous 24 h.',
    ],
    [
        'slug'          => 'placement-recrutement',
        'icone'         => 'equipe',
        'titre'         => 'Placement & recrutement',
        'texte'         => 'Nous trouvons pour vous les meilleurs talents, en adéquation avec vos exigences.',
        'mise_en_avant' => true,
        'accroche'      => 'Recrutez durablement les bons profils, sans y consacrer tout votre temps.',
        'intro'         => [
            "Un recrutement réussi se prépare : bien définir le poste, toucher les bons candidats, évaluer au-delà du CV. C'est un processus exigeant, souvent chronophage, et une erreur de recrutement coûte cher à l'entreprise comme au salarié.",
            "Avec son service de placement, CAFPM prend en charge tout ou partie de vos recrutements en CDD ou en CDI. Le candidat retenu est embauché directement par votre entreprise, et nous vous accompagnons jusqu'à son intégration.",
        ],
        'pour_qui' => [
            'Les PME qui ne disposent pas d’un service RH dédié',
            'Les entreprises qui recrutent sur des postes difficiles à pourvoir ou stratégiques',
            'Les organisations qui veulent gagner du temps sur le tri des candidatures',
            'Les structures qui ouvrent une nouvelle activité ou un nouveau site',
        ],
        'missions' => [
            'Définition du poste et du profil recherché, avec vous',
            "Rédaction et diffusion de l'offre, recherche active dans notre vivier de candidats",
            'Tri des candidatures et entretiens de présélection',
            'Évaluation des compétences techniques et comportementales, contrôle des références',
            'Présentation d’une sélection courte de candidats, avec notre avis argumenté',
            "Accompagnement jusqu'à l'embauche et suivi de l'intégration",
        ],
        'etapes' => [
            ['Cadrage du besoin', "Nous échangeons sur le poste, l'équipe, les compétences attendues et les conditions proposées."],
            ['Recherche et présélection', "Diffusion de l'offre, sollicitation de notre vivier, tri des candidatures et premiers entretiens."],
            ['Présentation des candidats', 'Vous recevez une sélection de profils évalués, accompagnée de notre avis sur chacun.'],
            ['Décision et intégration', "Vous rencontrez les finalistes et faites votre choix. Nous restons à vos côtés pendant la période d'intégration."],
        ],
        'avantages' => [
            ['Gain de temps', 'Vous ne rencontrez que des candidats présélectionnés et pertinents.'],
            ['Sélection rigoureuse', 'Entretiens structurés, évaluation des savoir-faire et du savoir-être, vérification des références.'],
            ['Connaissance du marché', 'Notre ancrage à Abidjan nous permet de bien connaître les profils disponibles et les attentes des candidats.'],
            ['Discrétion', 'Vos recrutements sensibles sont menés en toute confidentialité.'],
        ],
        'faq' => [
            ['Quels types de postes pouvez-vous recruter ?', "Nous intervenons principalement dans l'administration et les services, le commerce et la distribution, ainsi que le nettoyage et l'entretien, du poste opérationnel au poste d'encadrement. Pour un autre domaine, contactez-nous : nous vous dirons franchement si nous pouvons vous aider."],
            ['Quelle différence avec le travail temporaire ?', "En placement, le candidat devient votre salarié dès son embauche. En travail temporaire, il reste salarié de CAFPM pendant toute la durée de sa mission."],
            ['Comment sont fixés vos honoraires ?', 'Nos conditions sont détaillées dans une proposition commerciale établie après le cadrage de votre besoin, avant tout engagement de votre part.'],
            ['Combien de temps dure un recrutement ?', 'Cela dépend de la rareté du profil et de vos disponibilités pour les entretiens. Nous vous communiquons un calendrier prévisionnel dès le lancement de la mission.'],
        ],
        'cta_titre' => 'Un poste à pourvoir ?',
        'cta_texte' => 'Confiez-nous votre recrutement : nous revenons vers vous sous 24 h pour cadrer votre besoin.',
    ],
    [
        'slug'     => 'mise-a-disposition',
        'icone'    => 'calendrier',
        'titre'    => 'Mise à disposition',
        'texte'    => 'Externalisez la gestion de votre personnel et concentrez-vous sur votre activité.',
        'accroche' => 'Disposez d’équipes qualifiées sur la durée : CAFPM s’occupe du reste.',
        'intro'    => [
            "Vous avez besoin de personnel de façon régulière, sur plusieurs mois, sans vouloir gérer vous-même le recrutement, l'administration et les remplacements ? La mise à disposition de personnel est pensée pour vous.",
            "CAFPM recrute, emploie et gère les collaborateurs qui travaillent au sein de votre structure. Vous bénéficiez d'équipes stables et encadrées, tout en vous concentrant sur votre cœur de métier.",
        ],
        'pour_qui' => [
            'Les entreprises qui externalisent des fonctions support (accueil, secrétariat, services généraux, manutention…)',
            'Les organisations qui souhaitent maîtriser leurs effectifs et leurs coûts de personnel',
            "Les structures multi-sites qui veulent un interlocuteur unique",
            'Les projets de moyenne ou de longue durée',
        ],
        'missions' => [
            'Recrutement et constitution des équipes selon votre cahier des charges',
            'Gestion des contrats, de la paie et des déclarations sociales',
            'Gestion des absences, des congés et des remplacements',
            'Suivi de la qualité et points réguliers avec votre référent',
            'Propositions de formation pour faire progresser les équipes',
        ],
        'etapes' => [
            ['Étude de vos besoins', 'Postes, effectifs, horaires, lieux et durée : nous définissons ensemble le périmètre de la prestation.'],
            ['Proposition sur mesure', 'Nous vous remettons une offre détaillée : organisation, profils, modalités de suivi et tarifs.'],
            ['Mise en place des équipes', 'Recrutement, contrats, accueil sur site : les collaborateurs prennent leur poste.'],
            ['Pilotage continu', 'Un interlocuteur dédié suit la prestation, organise les remplacements et fait régulièrement le point avec vous.'],
        ],
        'avantages' => [
            ['Un interlocuteur unique', 'Un seul contact pour toutes les questions liées au personnel mis à disposition.'],
            ['Continuité de service', 'Les absences sont anticipées et les remplacements organisés par CAFPM.'],
            ['Coûts maîtrisés', 'Une facturation claire, sans les coûts cachés de la gestion administrative.'],
            ['Souplesse', 'Les effectifs évoluent avec votre activité, dans le cadre convenu ensemble.'],
        ],
        'faq' => [
            ["Quelle différence avec l'intérim ?", "L'intérim répond surtout à des besoins ponctuels et courts. La mise à disposition s'inscrit dans la durée, avec des équipes stables et un pilotage régulier de la prestation."],
            ['Qui encadre le personnel au quotidien ?', "Vous donnez les consignes de travail sur site. CAFPM reste l'employeur : contrat, paie et remplacements relèvent de notre responsabilité."],
            ['Peut-on ajuster les effectifs en cours de contrat ?', 'Oui, les modalités d’ajustement sont prévues au contrat. Il suffit d’en parler à votre interlocuteur CAFPM.'],
        ],
        'cta_titre' => 'Externalisez la gestion de votre personnel',
        'cta_texte' => 'Parlez-nous de votre organisation : nous vous proposons une prestation adaptée.',
    ],
    [
        'slug'     => 'formation-professionnelle',
        'icone'    => 'document',
        'titre'    => 'Formation professionnelle',
        'texte'    => 'Développez les compétences de vos équipes avec des programmes adaptés au marché.',
        'candidat' => true,
        'accroche' => "Développez les compétences de vos équipes et l'employabilité des talents.",
        'intro'    => [
            "Les métiers évoluent, les attentes des clients aussi. Former ses collaborateurs, c'est gagner en qualité de service, en sécurité et en motivation. Pour une personne en recherche d'emploi, c'est souvent la clé d'une embauche.",
            "CAFPM conçoit et organise des formations pratiques, orientées terrain, pour les entreprises comme pour les candidats. Les programmes sont adaptés au niveau des participants et aux réalités du marché ivoirien.",
        ],
        'pour_qui' => [
            'Les entreprises qui veulent renforcer les compétences de leurs équipes',
            "Les managers qui accueillent de nouveaux collaborateurs",
            "Les candidats et demandeurs d'emploi qui souhaitent améliorer leur employabilité",
            'Les intérimaires et collaborateurs mis à disposition par CAFPM',
        ],
        'missions' => [
            'Analyse de vos besoins de formation',
            'Programmes sur mesure ou modules existants : savoir-être professionnel, accueil et relation client, techniques de vente, bureautique, hygiène et sécurité, techniques d’entretien…',
            'Sessions organisées dans vos locaux ou dans un lieu adapté à Abidjan',
            'Mises en situation pratiques et exercices proches du terrain',
            'Évaluation des acquis en fin de session et bilan avec vous',
        ],
        'etapes' => [
            ['Diagnostic', 'Nous identifions les compétences à développer et le niveau des participants.'],
            ['Conception du programme', 'Contenu, durée, format et calendrier sont définis selon vos objectifs et vos contraintes.'],
            ['Animation', 'Des sessions dynamiques, alternant apports théoriques, exercices et mises en situation.'],
            ['Évaluation et suivi', 'Les acquis sont évalués et un bilan vous est remis, avec des pistes pour aller plus loin.'],
        ],
        'avantages' => [
            ['Pédagogie pratique', 'Des exercices et des cas concrets, directement applicables au poste de travail.'],
            ['Programmes adaptés', 'Un contenu ajusté au niveau des participants et aux réalités de votre secteur.'],
            ["Souplesse d'organisation", 'Dates, horaires et lieu définis selon votre activité.'],
            ["Tremplin vers l'emploi", 'Pour les candidats, la formation s’inscrit dans un parcours vers une mission ou un emploi.'],
        ],
        'faq' => [
            ['Proposez-vous des formations aux particuliers ?', "Oui. Créez votre profil candidat et indiquez-nous votre souhait de formation : nous vous informerons des prochaines sessions adaptées à votre projet."],
            ['Les formations sont-elles sur mesure ?', 'Pour les entreprises, oui : le contenu, la durée et le rythme sont définis avec vous après un diagnostic.'],
            ['Où se déroulent les formations ?', 'Dans vos locaux (intra-entreprise) ou dans un lieu adapté à Abidjan, selon le format retenu.'],
            ['Comment connaître les prochaines sessions ?', 'Contactez-nous ou abonnez-vous à notre newsletter pour être informé des sessions à venir.'],
        ],
        'cta_titre' => 'Faites grandir les compétences',
        'cta_texte' => 'Entreprise : déposez votre besoin de formation. Candidat : créez votre profil pour être informé des prochaines sessions.',
    ],
    [
        'slug'     => 'entretien-services',
        'icone'    => 'cube',
        'titre'    => 'Entretien & services',
        'texte'    => "Des équipes qualifiées pour l'entretien et la gestion de vos espaces professionnels.",
        'accroche' => 'Des espaces de travail propres, sains et accueillants, au quotidien.',
        'intro'    => [
            "La propreté de vos locaux influence directement la santé de vos équipes, l'image que vous donnez à vos clients et la durée de vie de vos équipements. Elle mérite des professionnels formés et bien encadrés.",
            "CAFPM met à votre service des agents d'entretien et des techniciens de surface qualifiés, pour des prestations régulières ou ponctuelles, adaptées à vos horaires et à la nature de vos espaces.",
        ],
        'pour_qui' => [
            'Bureaux, sièges sociaux et espaces administratifs',
            'Commerces, boutiques et surfaces de vente',
            'Résidences, parties communes et locaux professionnels',
            'Structures qui ont besoin d’un nettoyage ponctuel (après travaux, déménagement, événement)',
        ],
        'missions' => [
            'Nettoyage quotidien ou périodique des bureaux, sanitaires et espaces communs',
            'Entretien des sols et des vitres',
            'Remise en état après travaux, déménagement ou événement',
            "Mise à disposition d'agents d'entretien à temps plein ou à temps partiel",
            'Contrôles qualité réguliers et interlocuteur dédié',
        ],
        'etapes' => [
            ['Visite et évaluation', 'Nous visitons vos locaux pour comprendre vos attentes, vos contraintes et vos horaires.'],
            ['Cahier des charges et devis', 'Tâches, fréquences et moyens sont décrits précisément dans une proposition chiffrée.'],
            ['Démarrage de la prestation', 'Nos équipes interviennent selon le planning convenu, avec les consignes propres à votre site.'],
            ['Contrôle qualité', 'Des vérifications régulières sur site et des échanges avec vous garantissent un niveau de service constant.'],
        ],
        'avantages' => [
            ['Personnel formé', "Des agents formés aux bons gestes, aux règles d'hygiène et à l'utilisation des produits."],
            ['Horaires adaptés', 'Interventions tôt le matin, en journée ou après la fermeture, selon votre organisation.'],
            ['Qualité suivie', 'Des contrôles réguliers et un interlocuteur dédié pour vos demandes.'],
            ['Remplacements organisés', 'En cas d’absence, CAFPM organise le remplacement pour assurer la continuité du service.'],
        ],
        'faq' => [
            ['Fournissez-vous le matériel et les produits ?', 'Les modalités (matériel et produits fournis par CAFPM ou par votre entreprise) sont fixées dans le devis, selon vos préférences.'],
            ['Pouvez-vous intervenir en dehors des heures de bureau ?', 'Oui, les horaires d’intervention sont définis avec vous pour ne pas perturber votre activité.'],
            ['Proposez-vous des interventions ponctuelles ?', 'Oui : nettoyage après travaux, avant ou après un événement, remise en état de locaux… Décrivez-nous votre besoin pour recevoir un devis.'],
        ],
        'cta_titre' => 'Des locaux impeccables, sans y penser',
        'cta_texte' => 'Demandez une visite de vos locaux et recevez un devis adapté à votre besoin.',
    ],
];

/* --- Secteurs d'intervention (lien = page solution la plus pertinente) --- */
$secteurs = [
    ['icone' => 'puce',   'titre' => 'Administration & Services', 'texte' => 'Personnel administratif, secrétariat, accueil, assistance et services généraux.',                         'lien' => 'solution.php?s=placement-recrutement', 'libelle_lien' => 'Recruter dans ce secteur'],
    ['icone' => 'panier', 'titre' => 'Commerce & Distribution',   'texte' => 'Vendeurs, caissiers, commerciaux, magasiniers et personnel de distribution.',                              'lien' => 'solution.php?s=travail-temporaire',    'libelle_lien' => 'Renforcer vos équipes'],
    ['icone' => 'cube',   'titre' => 'Nettoyage & Entretien',     'texte' => "Agents d'entretien, techniciens de surface et équipes spécialisées pour tous types d'environnements.", 'lien' => 'solution.php?s=entretien-services',     'libelle_lien' => 'Découvrir nos prestations'],
];

/* --- Liste des domaines proposés dans les formulaires (listes déroulantes) --- */
$domaines = [
    'BTP & Construction',
    'Administration',
    'Logistique & Transport',
    'Commerce & Distribution',
    'Nettoyage & Entretien',
];

/* --- Filtres de la barre de recherche de profils --- */
$niveaux_experience = ['Débutant', '1 à 3 ans', '3 à 5 ans', 'Plus de 5 ans'];
$disponibilites     = ['Immédiate', 'Sous 15 jours', 'Sous 1 mois'];
$localisations      = [
    "Communes d'Abidjan" => ['Abobo', 'Adjamé', 'Attécoubé', 'Cocody', 'Koumassi', 'Marcory', 'Plateau', 'Port-Bouët', 'Treichville', 'Yopougon', 'Anyama', 'Bingerville', 'Songon'],
    'Autres villes'      => ['Bouaké', 'Daloa', 'Grand-Bassam', 'Korhogo', 'Man', 'San-Pédro', 'Yamoussoukro'],
];

/**
 * Toutes les localisations en une seule liste (sans les groupes),
 * utilisée pour vérifier la valeur envoyée par les formulaires.
 */
function toutes_localisations(): array
{
    global $localisations;
    return array_merge(...array_values($localisations));
}

/* --- Sujets du formulaire de contact (contrat avec api/contact.php) --- */
$sujets_contact = ['Demande de personnel', 'Candidature', 'Formation', 'Partenariat', 'Autre'];

/* ==========================================================================
   ACTUALITÉS
   - texte : résumé affiché sur les cartes
   - corps : article complet (actualite.php?a=<slug>)
   - date_iso : date au format AAAA-MM-JJ (balise <time>)
   ========================================================================== */
$actualites = [
    [
        'slug'      => 'cafpm-recrute-postes-administration',
        'date'      => '12 septembre 2025',
        'date_iso'  => '2025-09-12',
        'categorie' => 'Recrutement',
        'titre'     => 'CAFPM recrute : plusieurs postes disponibles en administration',
        'texte'     => 'Nous recherchons pour nos partenaires des profils qualifiés (assistant RH, secrétaire de direction) pour des missions de longue durée à Abidjan.',
        'solution'  => 'placement-recrutement',
        'corps'     => [
            ['p', "Pour accompagner le développement de plusieurs entreprises partenaires, CAFPM renforce son vivier de candidats dans les métiers de l'administration. Nous recherchons des profils qualifiés, notamment des assistants ressources humaines et des secrétaires de direction, pour des missions de longue durée à Abidjan."],
            ['h2', 'Les profils recherchés'],
            ['p', "Les postes proposés s'adressent à des professionnels rigoureux, organisés et à l'aise dans un environnement exigeant. Selon les missions, les principales responsabilités sont les suivantes :"],
            ['ul', [
                "Assistant(e) RH : suivi des dossiers du personnel, préparation des éléments de paie, gestion des absences et des congés, appui au recrutement et à l'intégration des nouveaux collaborateurs ;",
                "Secrétaire de direction : gestion de l'agenda et des déplacements de la direction, organisation des réunions, rédaction de courriers et de comptes rendus, classement et suivi de dossiers confidentiels.",
            ]],
            ['h2', 'Les qualités attendues'],
            ['p', "Au-delà des diplômes, nous attachons une grande importance au savoir-être : sens de la discrétion, capacité d'organisation, aisance relationnelle et bonne maîtrise des outils bureautiques (traitement de texte, tableur, messagerie). Une première expérience sur un poste similaire est appréciée ; les exigences précises de formation et d'expérience sont communiquées pour chaque mission."],
            ['h2', 'Comment postuler ?'],
            ['p', "La démarche est simple : créez votre profil candidat sur notre site en quelques minutes, en indiquant votre métier et votre domaine d'expertise, puis joignez votre CV au format PDF. Nos chargés de recrutement étudient chaque candidature avec attention. Si votre profil correspond à l'une de nos missions, nous vous contactons pour un entretien afin de mieux vous connaître et de vérifier l'adéquation avec les attentes de l'entreprise."],
            ['p', "Même si aucune mission ne correspond immédiatement à votre profil, votre candidature reste dans notre vivier : nous pourrons vous proposer d'autres opportunités par la suite, dans l'administration comme dans nos autres secteurs d'intervention."],
            ['h2', 'Entreprises : vous avez un besoin similaire ?'],
            ['p', "Vous recherchez vous aussi du personnel administratif, pour un remplacement, un renfort temporaire ou un recrutement durable ? Déposez votre besoin en ligne : un conseiller CAFPM vous recontacte sous 24 h pour en préciser les contours et vous proposer la solution la plus adaptée."],
        ],
    ],
    [
        'slug'      => 'formation-competences-employabilite',
        'date'      => '5 septembre 2025',
        'date_iso'  => '2025-09-05',
        'categorie' => 'Formation',
        'titre'     => 'Développez vos compétences pour une meilleure employabilité',
        'texte'     => 'Découvrez nos nouveaux modules de formation destinés aux candidats souhaitant renforcer leurs acquis techniques et comportementaux.',
        'solution'  => 'formation-professionnelle',
        'corps'     => [
            ['p', "Sur un marché de l'emploi de plus en plus concurrentiel, le diplôme ne suffit pas toujours à faire la différence. Les recruteurs recherchent des candidats rapidement opérationnels, capables de s'adapter et de bien représenter leur entreprise. Pour répondre à cette attente, CAFPM propose de nouveaux modules de formation destinés aux candidats qui souhaitent renforcer leurs acquis techniques et comportementaux."],
            ['h2', 'Des compétences techniques…'],
            ['p', 'Chaque métier repose sur des gestes et des savoirs précis. Nos modules techniques permettent de consolider ces bases ou de les mettre à jour. Parmi les thématiques abordées :'],
            ['ul', [
                'la bureautique et les outils numériques du quotidien professionnel ;',
                "l'accueil, la relation client et les techniques de vente ;",
                "les techniques d'entretien, l'hygiène et la sécurité au poste de travail.",
            ]],
            ['h2', '… et comportementales'],
            ['p', "Ponctualité, présentation, communication, travail en équipe, gestion du stress : ces compétences, souvent appelées « savoir-être », pèsent lourd dans une décision d'embauche. Elles sont travaillées lors d'ateliers pratiques, avec des mises en situation proches de la réalité du terrain : simulation d'entretien d'embauche, accueil d'un client mécontent, organisation d'une journée de travail…"],
            ['h2', "Une formation tournée vers l'emploi"],
            ['p', "Nos formations sont conçues à partir des besoins exprimés par les entreprises que nous accompagnons au quotidien. Elles visent un objectif concret : vous aider à décrocher une mission ou un emploi, puis à réussir dans votre poste. À l'issue de la formation, chaque participant fait le point avec un conseiller sur son projet professionnel et sur les opportunités correspondant à son profil."],
            ['h2', "Comment s'inscrire ?"],
            ['p', "Pour être informé des prochaines sessions, créez votre profil candidat sur notre site en précisant votre métier et votre domaine d'expertise. Vous pouvez aussi nous écrire via le formulaire de contact en choisissant le sujet « Formation », ou nous appeler directement. Le programme détaillé, les dates et les modalités de chaque session vous seront communiqués."],
            ['p', 'Vous êtes une entreprise et souhaitez former vos équipes ? CAFPM conçoit également des programmes sur mesure, organisés dans vos locaux ou dans un lieu adapté.'],
        ],
    ],
    [
        'slug'      => 'nettoyage-professionnel-atout',
        'date'      => '28 août 2025',
        'date_iso'  => '2025-08-28',
        'categorie' => 'Services',
        'titre'     => 'Le nettoyage professionnel, un atout pour mieux vous servir',
        'texte'     => "Un environnement de travail sain est primordial. Nos équipes d'entretien appliquent des protocoles stricts dans les espaces professionnels.",
        'solution'  => 'entretien-services',
        'corps'     => [
            ['p', "Un environnement de travail propre n'est pas un détail. Il contribue à la santé et au bien-être des collaborateurs, renvoie une image soignée aux clients et aux visiteurs, et prolonge la durée de vie des locaux et des équipements. C'est pourquoi les équipes d'entretien de CAFPM appliquent des protocoles stricts dans les espaces professionnels qui leur sont confiés."],
            ['h2', 'Des protocoles clairs et adaptés'],
            ['p', "Chaque prestation commence par une visite des locaux. Elle permet d'identifier les zones à traiter, la fréquence des interventions et les contraintes propres à chaque site : horaires d'ouverture, zones sensibles, surfaces particulières. Un cahier des charges précis est ensuite établi ; il détaille les tâches à réaliser (bureaux, sanitaires, espaces communs, sols, vitres) et le rythme de passage convenu."],
            ['p', "Nos agents travaillent selon des méthodes éprouvées : nettoyage du plus propre vers le plus sale et du haut vers le bas, matériel distinct selon les zones pour éviter la contamination croisée, dosage correct des produits et attention particulière aux points de contact fréquents comme les poignées, les interrupteurs, les rampes et les plans de travail."],
            ['h2', 'Du personnel formé et encadré'],
            ['p', "La qualité d'une prestation repose avant tout sur les personnes qui la réalisent. Nos agents d'entretien et techniciens de surface sont sélectionnés avec soin, puis formés aux bons gestes, aux règles d'hygiène et de sécurité et à l'utilisation des produits. Ils sont encadrés et bénéficient d'un suivi régulier."],
            ['h2', 'Un suivi de la qualité dans la durée'],
            ['p', 'Des contrôles réguliers sont réalisés sur site afin de vérifier le respect du cahier des charges. Vos remarques sont prises en compte rapidement, et les absences sont gérées par CAFPM pour assurer la continuité du service. Vous disposez d’un interlocuteur dédié pour toute demande d’ajustement.'],
            ['h2', 'Une solution adaptée à chaque structure'],
            ['p', "Bureaux, commerces, résidences ou locaux professionnels : que vous ayez besoin d'un entretien quotidien, d'un passage hebdomadaire ou d'un nettoyage ponctuel après travaux ou avant un événement, CAFPM vous propose une prestation sur mesure. Décrivez-nous votre besoin : nous organisons une visite et vous remettons un devis adapté."],
        ],
    ],
];

/* --- Témoignages (avatar = image OU null pour un rond jaune) --- */
$temoignages = [
    ['texte' => "CAFPM nous accompagne dans la gestion de nos besoins en personnel temporaire depuis plusieurs années. Leur réactivité fait toute la différence.", 'nom' => 'Jean-Marc D.', 'poste' => 'DRH, Société Industrielle CI', 'avatar' => 'assets/images/entreprise.png'],
    ['texte' => "Grâce à CAFPM Match, nous avons recruté notre directeur commercial en un temps record. Une sélection pointue et un suivi impeccable.",         'nom' => 'Awa T.',       'poste' => 'CEO, Tech Africa Group',       'avatar' => 'assets/images/candidat.png'],
    ['texte' => "Leur service d'entretien est d'une grande rigueur. Nos bureaux sont toujours impeccables, et le personnel est très professionnel.",           'nom' => 'Marc C.',      'poste' => 'Directeur Général, Logistics Pro', 'avatar' => null],
];

/**
 * Affiche une icône SVG à partir de son nom (clé du tableau $icones).
 */
function icone(string $nom, int $taille = 24, string $couleur = 'currentColor', float $epaisseur = 2): string
{
    global $icones;
    return '<svg width="' . $taille . '" height="' . $taille . '" viewBox="0 0 24 24" fill="none" stroke="' . $couleur . '" stroke-width="' . $epaisseur . '" aria-hidden="true">'
         . ($icones[$nom] ?? '') . '</svg>';
}
