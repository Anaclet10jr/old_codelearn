-- Run against the CodeLearn database:
--   sqlite3 ../data/elearning.sqlite < queries.sql

-- Most popular courses
SELECT c.title, COUNT(e.user_id) AS students
FROM courses c
LEFT JOIN enrollments e ON e.course_id = c.id
GROUP BY c.id
ORDER BY students DESC;

-- Average quiz score per course
SELECT c.title, ROUND(AVG(a.score * 100.0 / a.total), 1) AS avg_pct
FROM quiz_attempts a
JOIN courses c ON c.id = a.course_id
GROUP BY c.id
ORDER BY avg_pct DESC;
