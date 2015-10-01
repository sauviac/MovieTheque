<?php
function Redim_Pict ($Path, $Image, $LargeurMax, $HauteurMax)

// Taille['largeur']=largeur finale de l'image à afficher
// Taille['hauteureur']=hauteur finale de l'image à afficher

{
$NomImage=$Path.$Image;
//echo $NomImage;

$size = GetImageSize($NomImage);
$Taille['Largeur'] = $size[0];  // largeur en pixels
$Taille['Hauteur'] = $size[1];  // hauteur en pixels
$Rapport=$Taille['Largeur'] / $Taille['Hauteur'] ;

//if ($Taille['Largeur'] < $Taille['Hauteur'] )
//{
	$H=round($LargeurMax / $Rapport);
	if ( $H > $HauteurMax ) 
	{
		$H=$HauteurMax;
		$L=round($HauteurMax*$Rapport);
	} 
	else 
	{ 
		$L=$LargeurMax;
	}
//} else
//{
//	$H=round($LargeurMax / $Rapport);
//}

$Taille['Largeur'] = $L;
$Taille['Hauteur'] = $H;


return $Taille;
}
?> 