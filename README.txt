Below lists a functionality/role of each SQL query in our website:

INSERT operation:
  inserts an art owner into ArtOwner 

DELETE operation:
  deletes an art owner from ArtOwner

UPDATE operation:
  updates one of first name, last name, or email of an art owner from ArtOwner

SELECTION operation:
  allows ArtOwner user to select their choice of medium and attributes of artworks with a specific price or year range
  
PROJECTION operation:
  displays first name, last name, and email of all existing art owners

JOIN operation:
  allows user to input an artwork name to search for a list of artists whose art pieces have that same name

AGGREAGTION WITH GROUP BY operation:
  displays total exhibition fees grouped by medium for the ArtOwner user (helpful for them to see how much revenue their artworks generate)

AGGREGATION WITH HAVING operation:
  displays all art owners who own more than 2 art pieces in the gallery (these art owners are considered VIPs)

NESTED AGGREGATION WITH GROUP BY operation:
  displays all artists who have created artworks with average price higher than the average price of all artworks in the gallery (these artists are considered VIP as well)

DIVISION operation:
  displays all floor numbers that have hosted all of exhibitions 
