<?php



/**
 * Replace all occurrences of the search string with the replacement string.
 *
 * @param mixed $search
 * @param mixed $replace
 * @param mixed $subject
 * @param int $count
 *
 * @return mixed
 */
function mb_str_replace($search, $replace, $subject, &$count = 0)
{
    if (!is_array($subject)) {
        // Normalize $search and $replace so they are both arrays of the same length
        $searches = is_array($search) ? array_values($search) : array($search);
        $replacements = is_array($replace) ? array_values($replace) : array($replace);
        $replacements = array_pad($replacements, count($searches), "");

        foreach ($searches as $key => $search) {
            $parts = mb_split(preg_quote($search), $subject);
            $count += count($parts) - 1;
            $subject = implode($replacements[$key], $parts);
        }
    } else {
        // Call mb_str_replace for each subject in array, recursively
        foreach ($subject as $key => $value) {
            $subject[$key] = mb_str_replace($search, $replace, $value, $count);
        }
    }

    return $subject;
}