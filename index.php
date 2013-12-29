<?php
session_start();
require("config.php");
require("functions.php");
if($_GET['subject']) {
    if(pf_check_number($_GET['subject']) == TRUE) {
        $validsub = $_GET['subject'];
    }
    else {
        header("Location: " . $config_basedir);
    }
}
require("header.php");

if($_GET['subject']) {
    $subsql = "SELECT users.username, subjects.* FROM subjects
LEFT JOIN users ON subjects.owner_id = users.id
WHERE subjects.id = " . $validsub . ";";
    $subresult = mysql_query($subsql);
    $subrow = mysql_fetch_assoc($subresult);
    echo "<h1>" . $subrow['subject'] . " Summary</h1>";
    if($subrow['owner_id'] == 0) {
        echo "This subject has no owner.";
        if($_SESSION['SESS_USERNAME']) {
            echo " If you would like to apply to own this subject,
click <a href='applysubowner.php?subject=" . $subject
                    . "'>here</a>.";
        }
    }
    else {
        echo "This subject is owned by <strong>" .
                $subrow['username'] . "</strong>.";
    }
    echo "<p><i>" . $subrow['blurb'] . "</i></p>";
    $topsql = "SELECT count(distinct(topics.id)) AS numtopics,
count(questions.id) AS numquestions FROM subjects LEFT JOIN
topics ON subjects.id = topics.subject_id LEFT JOIN questions
ON topics.id = questions.topic_id WHERE subjects.id = "
            . $validsub . " AND active = 1;";
    $topresult = mysql_query($topsql);
    $toprow = mysql_fetch_assoc($topresult);
    echo "<table class='visible' cellspacing=0 cellpadding=5>";
    echo "<tr><th class='visible' colspan=2>Statistics</th></tr>";
    echo "<tr>";
    echo "<td>Total Topics</td><td>" . $toprow['numtopics']
            . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Total Questions</td><td>" . $toprow['numquestions']
            . "</td>";
    echo "</tr>";
    echo "</table>";
}
else
{
    $latqsql = "SELECT questions.id, question, subject
FROM subjects, questions, topics WHERE questions.topic_id =
topics.id AND topics.subject_id = subjects.id AND active = 1
ORDER BY questions.dateadded DESC;";
    $latqresult = mysql_query($latqsql);
    $latqnumrows = mysql_num_rows($latqresult);
    echo "<h1>Latest Questions</h1>";
    if($latqnumrows == 0) {
        echo "No questions!";
    }
    else {
        echo "<ul>";
        while($latqrow = mysql_fetch_assoc($latqresult)) {
            echo "<li><a href='answer.php?id=" . $latqrow['id'] .
                    "'>" . $latqrow['question'] . "</a> (<i>" . $latqrow['subject'] .
                    "</i>)</li>";
        }
        echo "</ul>";
    }
}
require("footer.php");
?>