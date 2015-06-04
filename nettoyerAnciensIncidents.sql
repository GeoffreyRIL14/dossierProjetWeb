CREATE DEFINER=`root`@`localhost` 
PROCEDURE `nettoyerAnciensIncidents`()
    MODIFIES SQL DATA
DELETE FROM incident
WHERE (Now() - incident.dateHeureIncident  14400)