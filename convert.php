<?php

function determineType($text)
{
    $text = strtolower($text);

    if (false !== strpos($text, 'kentucky') || false !== strpos($text, 'lager') || false !== strpos($text, 'strong european beer') || false !== strpos($text, 'amber bitter european beer') || false !== strpos($text, 'california common') || false !== strpos($text, 'roggenbier')) {
        return 'Lager';
    }

    if (false !== strpos($text, 'ale') || false !== strpos($text, 'ipa') || false !== strpos($text, 'wheat') || false !== strpos($text, 'british bitter') || false !== strpos($text, 'pale commonwealth beer') || false !== strpos($text, 'brown british beer') || false !== strpos($text, 'irish beer') || false !== strpos($text, 'dark british beer') || false !== strpos($text, 'american porter and stout') || false !== strpos($text, 'piwo grodziskie') || false !== strpos($text, 'sahti')) {
        return 'Ale';
    }

    if (false !== strpos($text, 'gose') || false !== strpos($text, 'lichtenhainer') || false !== strpos($text, 'pre-prohibition porter') || false !== strpos($text, 'fruit beer') || false !== strpos($text, 'spiced beer') || false !== strpos($text, 'smoked beer') || false !== strpos($text, 'specialty beer') || false !== strpos($text, 'wood beer') || false !== strpos($text, 'alternative fermentables beer')) {
        return 'Mixed';
    }
}

function determineOrigin($text)
{
    $text = strtolower($text);

    if (false !== strpos($text, 'kentucky') || false !== strpos($text, 'american') || false !== strpos($text, 'pre-prohibition')) {
        return 'North American';
    }

    if (false !== strpos($text, 'international')) {
        return 'Other';
    }

    if (false !== strpos($text, 'gose') || false !== strpos($text, 'english') || false !== strpos($text, 'european') || false !== strpos($text, 'german') || false !== strpos($text, 'scottish') || false !== strpos($text, 'irish') || false !== strpos($text, 'belgian') || false !== strpos($text, 'trappist') || false !== strpos($text, 'british') || false !== strpos($text, 'czech') || false !== strpos($text, 'lichtenhainer') || false !== strpos($text, 'london') || false !== strpos($text, 'piwo grodziskie') || false !== strpos($text, 'roggenbier') || false !== strpos($text, 'sahti')) {
        return 'European';
    }

    if (false !== strpos($text, 'australian')) {
        return 'Australian';
    }

    if (false !== strpos($text, 'specialty ipa') || false !== strpos($text, 'fruit beer') || false !== strpos($text, 'spiced beer') || false !== strpos($text, 'smoked beer') || false !== strpos($text, 'specialty beer') || false !== strpos($text, 'wood beer') || false !== strpos($text, 'alternative fermentables beer')) {
        return 'Other';
    }
}

$jsonFileContents = file_get_contents(__DIR__.'/json/styleguide-2015.json');
$bjcpStyleGuideline = json_decode($jsonFileContents, true);

$categories = $bjcpStyleGuideline['styleguide']['class'][0]['category'];

$styles = [];
foreach ($categories as $category) {
    $parent = [];
    $parent['category'] = "'".addslashes($category['name'])."'";
    $parent['category_identifier'] = "'".addslashes($category['id'])."'";
    $parent['type'] = determineType($parent['category']);
    $parent['origin'] = determineOrigin($parent['category']);
    foreach ($category['subcategory'] as $subcategory) {
        $child = [];
        $child['style_identifier'] = "'".addslashes($subcategory['id'])."'";
        $child['name'] = "'".addslashes($subcategory['name'])."'";

        if (empty($parent['type'])) {
            $parent['type'] = determineType($child['name']);
        }

        if (empty($parent['origin'])) {
            $parent['origin'] = determineOrigin($child['name']);
        }

        $child['description'] = "'".addslashes(trim($subcategory['impression'].' '.$subcategory['aroma'].' '.$subcategory['appearance'].' '.$subcategory['flavor'].' '.$subcategory['mouthfeel'].' '.$subcategory['comments'].' '.$subcategory['history'].' '.$subcategory['ingredients'].' '.$subcategory['comparison']))."'";
        $child['impression'] = trim("'".addslashes($subcategory['impression'])."'");
        $child['aroma'] = trim("'".addslashes($subcategory['aroma'])."'");
        $child['appearance'] = trim("'".addslashes($subcategory['appearance'])."'");
        $child['flavor'] = trim("'".addslashes($subcategory['flavor'])."'");
        $child['mouthfeel'] = trim("'".addslashes($subcategory['mouthfeel'])."'");
        $child['comments'] = trim("'".addslashes($subcategory['comments'])."'");
        $child['history'] = trim("'".addslashes($subcategory['history'])."'");
        $child['ingredients'] = trim("'".addslashes($subcategory['ingredients'])."'");
        $child['comparison'] = trim("'".addslashes($subcategory['comparison'])."'");
        $child['og_min'] = $subcategory['stats']['og']['low'] ?? 0;
        $child['og_max'] = $subcategory['stats']['og']['high'] ?? 0;
        if (!empty($subcategory['stats']['og']['low'])) {
            $child['og_plato_min'] = round(((-463.37) + (668.72 * $subcategory['stats']['og']['low']) - (205.35 * pow($subcategory['stats']['og']['low'], 2))), 2);
        } else {
            $child['og_plato_min'] = 0;
        }
        if (!empty($subcategory['stats']['og']['high'])) {
            $child['og_plato_max'] = round(((-463.37) + (668.72 * $subcategory['stats']['og']['high']) - (205.35 * pow($subcategory['stats']['og']['high'], 2))), 2);
        } else {
            $child['og_plato_max'] = 0;
        }
        $child['fg_min'] = $subcategory['stats']['fg']['low'] ?? 0;
        $child['fg_max'] = $subcategory['stats']['fg']['high'] ?? 0;
        if (!empty($subcategory['stats']['fg']['low'])) {
            $child['fg_plato_min'] = round(((-463.37) + (668.72 * $subcategory['stats']['fg']['low']) - (205.35 * pow($subcategory['stats']['fg']['low'], 2))), 2);
        } else {
            $child['fg_plato_min'] = 0;
        }
        if (!empty($subcategory['stats']['fg']['high'])) {
            $child['fg_plato_max'] = round(((-463.37) + (668.72 * $subcategory['stats']['fg']['high']) - (205.35 * pow($subcategory['stats']['fg']['high'], 2))), 2);
        } else {
            $child['fg_plato_max'] = 0;
        }
        $child['abv_min'] = $subcategory['stats']['abv']['low'] ?? 0;
        $child['abv_max'] = $subcategory['stats']['abv']['high'] ?? 0;
        $child['abw_min'] = ($subcategory['stats']['abv']['low'] * .8) ?? 0;
        $child['abw_max'] = ($subcategory['stats']['abv']['high'] * .8) ?? 0;
        $child['ibu_min'] = $subcategory['stats']['ibu']['low'] ?? 0;
        $child['ibu_max'] = $subcategory['stats']['ibu']['high'] ?? 0;
        $child['srm_min'] = $subcategory['stats']['srm']['low'] ?? 0;
        $child['srm_max'] = $subcategory['stats']['srm']['high'] ?? 0;
        $child['ebc_min'] = ($subcategory['stats']['srm']['low'] * 1.97) ?? 0;
        $child['ebc_max'] = ($subcategory['stats']['srm']['high'] * 1.97) ?? 0;
        $child['guideline'] = "'BJCP'";
        $child['year'] = 2015;

        $parent['type'] = "'".$parent['type']."'";
        $parent['origin'] = "'".$parent['origin']."'";

        $styles[] = array_merge($parent, $child);

        $parent['type'] = determineType($parent['category']);
        $parent['origin'] = determineOrigin($parent['category']);
    }
}

//print_r($styles);

// foreach ($styles as $style) {
//     echo $style['category'].' - '.$style['name'].' - '.$style['type'].' - '.$style['origin']."\n";
// }

$values = [];
foreach ($styles as $style) {
    $values[] = '('.implode(',', $style).')';
}

$query = sprintf('INSERT INTO beer_styles (%s) VALUES %s', implode(',', array_keys($styles[0])), implode(',', $values));
$query = str_replace("''", 'null', $query);

file_put_contents('insert.sql', $query);
