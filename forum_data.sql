-- ── Sujets ────────────────────────────────────────────────────────────────────

INSERT INTO subjects (id, title, pseudo) VALUES
(2,  'Comment configurer une plage DHCP sur son réseau', 'netadmin'),
(3,  'DHCP ne distribue plus d adresses IP', 'user42'),
(4,  'Bail DHCP trop court, comment l augmenter ?', 'sysop'),
(5,  'Configurer BIND9 pour une zone locale', 'dnsmaster'),
(6,  'Résolution DNS qui ne fonctionne pas', 'helpdesk'),
(7,  'Créer une zone DNS interne avec BIND', 'netadmin'),
(8,  'Mettre en place des règles iptables de filtrage', 'sysop'),
(9,  'Bloquer l accès Internet à certains appareils', 'parentalctl'),
(10, 'Configurer un pare-feu avec iptables', 'secuadmin'),
(11, 'Restriction d accès par adresse MAC', 'netadmin'),
(12, 'Partage de connexion entre deux interfaces réseau', 'routeur'),
(13, 'NAT et routage vers Internet', 'sysop'),
(14, 'Les appareils n ont plus accès à Internet après changement de config', 'helpdesk'),
(15, 'Configurer le forwarding IP sur Debian', 'linuxuser'),
(16, 'Choisir le bon masque de sous-réseau', 'netadmin'),
(17, 'Réseau saturé, augmenter la capacité d adresses', 'sysop'),
(18, 'Filtrage par adresse MAC avec iptables', 'secuadmin'),
(19, 'Bloquer un appareil par son adresse matérielle', 'parentalctl'),
(20, 'Restaurer une sauvegarde de configuration réseau', 'sysop'),
(21, 'Fichier de configuration manquant après restauration', 'helpdesk'),
(22, 'Passer d un sous-réseau /24 à /23 pour plus d adresses', 'netadmin'),
(23, 'Un appareil bloqué continue d accéder au réseau', 'secuadmin'),
(24, 'Aucune règle MASQUERADE dans iptables, Internet cassé', 'routeur'),
(25, 'Sauvegarde ancienne incompatible avec la config actuelle', 'sysop');

-- ── Messages ──────────────────────────────────────────────────────────────────

INSERT INTO messages (content, pseudo, date, subject_id) VALUES

-- Sujet 2 : plage DHCP
('Bonjour, je cherche à configurer une plage DHCP sur mon réseau interne. Quelqu un peut m aider ?', 'user42', '2026-01-10 09:12:00', 2),
('Tu dois éditer /etc/dhcp/dhcpd.conf et ajouter une ligne range 192.168.1.100 192.168.1.200.', 'netadmin', '2026-01-10 09:45:00', 2),
('Merci ! Et pour le masque je mets quoi ?', 'user42', '2026-01-10 10:02:00', 2),
('option subnet-mask 255.255.255.0 dans le bloc subnet.', 'netadmin', '2026-01-10 10:15:00', 2),

-- Sujet 3 : DHCP ne distribue plus
('Depuis ce matin mon serveur DHCP ne donne plus d adresses. Les clients restent en 169.254.x.x.', 'helpdesk', '2026-02-03 08:30:00', 3),
('Vérifie que dhcpd tourne bien : systemctl status isc-dhcp-server', 'sysop', '2026-02-03 08:45:00', 3),
('Le service était arrêté ! Un redémarrage a suffi. Merci.', 'helpdesk', '2026-02-03 09:00:00', 3),

-- Sujet 4 : bail DHCP
('Mon bail DHCP est de 600 secondes, les appareils se déconnectent souvent. Comment augmenter ?', 'sysop', '2026-02-14 14:20:00', 4),
('Change default-lease-time et max-lease-time dans dhcpd.conf. Par exemple 86400 pour 24h.', 'netadmin', '2026-02-14 14:35:00', 4),

-- Sujet 5 : BIND9
('Je veux que mes appareils se résolvent par nom sur mon réseau local avec BIND9, par où commencer ?', 'dnsmaster', '2026-01-20 11:00:00', 5),
('Crée un fichier de zone dans /etc/bind/ et ajoute une entrée dans named.conf.local.', 'netadmin', '2026-01-20 11:30:00', 5),
('Tu peux t inspirer du fichier db.local comme template pour ta zone.', 'sysop', '2026-01-20 11:45:00', 5),

-- Sujet 6 : résolution DNS
('La résolution DNS ne fonctionne plus après un redémarrage. nslookup ne répond pas.', 'linuxuser', '2026-03-05 16:00:00', 6),
('Vérifie /etc/resolv.conf et que bind9 est bien lancé.', 'dnsmaster', '2026-03-05 16:20:00', 6),
('Le fichier resolv.conf avait été écrasé par dhclient. Problème réglé.', 'linuxuser', '2026-03-05 16:50:00', 6),

-- Sujet 8 : iptables filtrage
('Comment mettre en place un filtrage iptables pour bloquer le trafic entre mes interfaces ?', 'secuadmin', '2026-01-28 10:00:00', 8),
('iptables -P FORWARD DROP puis tu ajoutes des règles ACCEPT pour ce que tu veux autoriser.', 'sysop', '2026-01-28 10:20:00', 8),
('Et pour que les règles survivent au reboot ?', 'secuadmin', '2026-01-28 10:35:00', 8),
('Utilise iptables-save > /etc/iptables/rules.v4 et installe iptables-persistent.', 'netadmin', '2026-01-28 10:50:00', 8),

-- Sujet 9 : bloquer accès Internet
('Je veux bloquer l accès Internet de certains appareils le soir. Comment faire ?', 'parentalctl', '2026-02-20 19:00:00', 9),
('Tu peux utiliser iptables avec l option --mac-source pour cibler par adresse MAC.', 'netadmin', '2026-02-20 19:15:00', 9),
('Exemple : iptables -A FORWARD -m mac --mac-source AA:BB:CC:DD:EE:FF -j DROP', 'sysop', '2026-02-20 19:30:00', 9),
('Super, ça marche ! Merci beaucoup.', 'parentalctl', '2026-02-20 20:00:00', 9),

-- Sujet 12 : partage de connexion
('Comment partager ma connexion Internet entre eth0 (WAN) et eth1 (LAN) ?', 'routeur', '2026-01-15 14:00:00', 12),
('Active le forwarding : echo 1 > /proc/sys/net/ipv4/ip_forward et ajoute une règle MASQUERADE.', 'sysop', '2026-01-15 14:20:00', 12),
('iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE', 'netadmin', '2026-01-15 14:25:00', 12),
('Merci ! Les clients ont maintenant accès à Internet.', 'routeur', '2026-01-15 15:00:00', 12),

-- Sujet 14 : plus d accès Internet
('Après avoir rechargé une ancienne config, mes appareils n ont plus accès à Internet.', 'helpdesk', '2026-03-12 09:00:00', 14),
('Vérifie que ip_forward est à 1 et qu une règle MASQUERADE existe dans la table nat.', 'sysop', '2026-03-12 09:20:00', 14),
('Il manquait la règle MASQUERADE. Tout est rentré dans l ordre.', 'helpdesk', '2026-03-12 09:40:00', 14),

-- Sujet 16 : masque de sous-réseau
('Comment choisir le bon masque pour mon réseau avec une vingtaine d appareils ?', 'netadmin', '2026-01-05 10:00:00', 16),
('Un /24 (255.255.255.0) te donne 254 adresses utilisables, largement suffisant.', 'sysop', '2026-01-05 10:15:00', 16),
('Si tu veux plus petit, un /28 donne 14 adresses. Mais reste sur /24 pour de la marge.', 'netadmin', '2026-01-05 10:30:00', 16),

-- Sujet 18 : filtrage MAC
('Est-ce qu on peut filtrer par adresse MAC avec iptables ?', 'secuadmin', '2026-02-08 11:00:00', 18),
('Oui avec le module mac : iptables -m mac --mac-source XX:XX:XX:XX:XX:XX -j DROP', 'netadmin', '2026-02-08 11:20:00', 18),
('Attention, le filtrage MAC ne fonctionne que sur le même segment réseau (couche 2).', 'sysop', '2026-02-08 11:35:00', 18),

-- Sujet 20 : restauration sauvegarde
('Comment restaurer proprement une sauvegarde de configuration réseau ?', 'sysop', '2026-03-18 08:00:00', 20),
('Copie les fichiers interfaces, dhcpd.conf et recharge les services correspondants.', 'netadmin', '2026-03-18 08:30:00', 20),
('Pour iptables : iptables-restore < /chemin/vers/sauvegarde/iptables', 'secuadmin', '2026-03-18 08:45:00', 20),

-- Sujet 23 : appareil bloqué qui passe quand même
('J ai une règle DROP pour une adresse MAC mais l appareil accède quand même au réseau.', 'secuadmin', '2026-04-01 15:00:00', 23),
('La règle MAC ne fonctionne que sur le réseau local direct. Si l appareil passe par un switch managé ça peut poser problème.', 'netadmin', '2026-04-01 15:20:00', 23),
('Aussi vérifie l ordre de tes règles, un ACCEPT avant ton DROP annule le blocage.', 'sysop', '2026-04-01 15:35:00', 23),

-- Sujet 24 : MASQUERADE manquant
('Mes clients ont une IP locale mais ne peuvent pas aller sur Internet, pas de MASQUERADE dans iptables.', 'routeur', '2026-04-10 17:00:00', 24),
('Ajoute : iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE', 'netadmin', '2026-04-10 17:10:00', 24),
('Et vérifie que ip_forward est bien activé en parallèle.', 'sysop', '2026-04-10 17:15:00', 24),
('Parfait, ça fonctionne maintenant !', 'routeur', '2026-04-10 17:30:00', 24);
