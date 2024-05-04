<?php


function makeTruncatedDef($definition)
{
    // REGEX for identify number of definitons inside Definition1
    preg_match_all('/(\d+\.)/', $definition, $matches);
    // Get index of first point following number greater than 3
    $lastMatchIndex = false;
    foreach ($matches[0] as $key => $match) {
        if ((int)$matches[1][$key] >  3) {
            $lastMatchIndex = $key;
            break;
        }
    }
    // Cut text beyond 3. point
    if ($lastMatchIndex!== false) {
        $truncatedDefinition = substr($definition,  0, strpos($definition, $matches[0][$lastMatchIndex]));
    } else {
        //if its smaller than 4 points, show entire definition
        $truncatedDefinition = $definition;
    }
    
    return $truncatedDefinition;
}
