<?php
//Знайти ті слова, які в даному тексті зустрічаються найбільшу кількість раз.

function find_longest_subseq($word)
{
    $subseqs = preg_split('/[aeuio]/i', $word, -1, PREG_SPLIT_NO_EMPTY);
    $max = 0;

    foreach ($subseqs as $subword) {
        if (strlen($subword) > $max) $max = strlen($subword);
    }

    return ($max);
}

$file_content = file_get_contents('input.txt');
$count = preg_match_all('/\b([a-z]){1,30}\b/i', $file_content, $matches);

$words = $matches[0];
$words = array_unique($words);
$maxlen = 0;

foreach ($words as $word) {
    $len = find_longest_subseq($word);
    if ($len > $maxlen) {
        $results = [];
        $maxlen = $len;
    }

    if ($len == $maxlen) $results[] = $word;
}
