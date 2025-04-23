<?php
require('php/config.php');
// Fetch comments
$result = $mysqli->query("SELECT comments FROM satisfaction WHERE comments IS NOT NULL");
$allComments = '';
while ($row = $result->fetch_assoc()) {
    $allComments .= ' ' . $row['comments'];
}

// Convert to lowercase
$allComments = strtolower($allComments);

// Remove punctuation
$allComments = preg_replace('/[^\p{L}\p{N}\s]/u', '', $allComments);

// Define stop words
$stopWords = ['a'];
// $stopWords = ['a', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'for', 'from',
//               'has', 'he', 'in', 'is', 'it', 'its', 'of', 'on', 'that', 'the',
//               'to', 'was', 'were', 'will', 'with', 'i', 'you', 'your', 'we',
//               'they', 'them', 'this', 'but', 'or', 'if', 'then', 'so', 'than'];

// Remove stop words
$words = explode(' ', $allComments);
$filteredWords = array_filter($words, function($word) use ($stopWords) {
    return !in_array($word, $stopWords) && strlen($word) > 5;
});

// Count word frequencies
$wordFrequencies = array_count_values($filteredWords);

// Sort by frequency
arsort($wordFrequencies);

// Prepare data for word cloud (top 100 words)
$wordCloudData = array_slice($wordFrequencies, 0, 100);

// Output as JSON
header('Content-Type: application/json');
echo json_encode($wordCloudData);
?>
