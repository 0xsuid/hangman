<?php

include 'ascii_hangman.php';

echo "--------------------------- \n";
echo "Welcome to the Hangman Game \n";
echo "--------------------------- \n\n";

function customReplace($source, $target, $val)
{
    // Takes first array as source input from which we map where value is located,
    // Then we replace those values by index in target array.
    $all_pos = array_fill_keys(array_keys($source, $val), $val);
    return array_replace($target, $all_pos);
}

function randomWord($filename)
{
    // Source - https://gist.github.com/hugsy/8910dc78d208e40de42deb29e62df913
    $word_list = explode("\n", file_get_contents($filename));
    $rand_num = array_rand($word_list, 1);
    $rand_word = str_split($word_list[$rand_num]);
    return $rand_word;
}

function clearScreen()
{
    echo "\e[H\e[J";
}

// Loading dict/file and select random from it
$word = randomWord('english-adjectives.txt');
$wordlen = count($word);
$wrong = 0;
$finished = 0;

// creating one empty array with same length of word chars filled with '_'
$guessed_chars = array_fill(0, $wordlen, '_');

// add '-' to guessed chars array if it found in words
if (in_array('-', $word)) {
    $guessed_chars = customReplace($word, $guessed_chars, '-');
}

while (!$finished) {
    // Print Ascii art of hangman & Guessed characters
    echo $ascii_art[$wrong] . "\n\n";
    echo 'Word: ' . implode($guessed_chars) . "\n\n";

    $input = readline("Please Provide an input: ");

    // check if input have length of more then one character.
    if (strlen($input) > 1) {
        echo "\n--------------------------- \n";
        echo "Please enter only one char. \n";
        echo "--------------------------- \n";
        continue;
    }

    if (in_array($input, $guessed_chars)) {
        echo "\n----------------------------------- \n";
        echo "You have already entered this char. \n";
        echo "----------------------------------- \n";
        continue;
    }

    // check if input character exists in word
    if (!in_array($input, $word)) {
        $wrong++;
        if ($wrong == 6) {
            echo "Answer:" . implode($word);
            echo $ascii_art_lost;
            $finished = 1;
        }
    } else {
        $guessed_chars = customReplace($word, $guessed_chars, $input);
    }

    // check if all chars are guessed.
    if ($guessed_chars == $word) {
        echo $ascii_art_won;
        $finished = 1;
    }

    if (!$finished) {
        clearScreen();
    }
}
