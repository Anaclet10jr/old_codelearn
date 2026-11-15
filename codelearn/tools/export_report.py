#!/usr/bin/env python3
"""
Export a progress report from the CodeLearn database to CSV.

Usage:
    python tools/export_report.py                 # writes report.csv
    python tools/export_report.py my_report.csv   # custom file name

Uses only the Python standard library (sqlite3, csv, pathlib).
"""
import csv
import sqlite3
import sys
from pathlib import Path

DB = Path(__file__).resolve().parent.parent / "data" / "elearning.sqlite"

QUERY = """
SELECT u.name,
       u.email,
       c.title                              AS course,
       (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id)           AS lessons_total,
       (SELECT COUNT(*) FROM progress p JOIN lessons l ON l.id = p.lesson_id
         WHERE p.user_id = u.id AND l.course_id = c.id)                    AS lessons_done,
       (SELECT MAX(ROUND(a.score * 100.0 / a.total))
          FROM quiz_attempts a WHERE a.user_id = u.id AND a.course_id = c.id) AS best_quiz_pct
FROM users u
JOIN enrollments e ON e.user_id = u.id
JOIN courses c     ON c.id = e.course_id
WHERE u.role = 'student'
ORDER BY u.name, c.title
"""


def main() -> int:
    if not DB.exists():
        print(f"Database not found: {DB}\nOpen the website once so it is created.")
        return 1

    out = Path(sys.argv[1] if len(sys.argv) > 1 else "report.csv")
    with sqlite3.connect(DB) as conn:
        cur = conn.execute(QUERY)
        headers = [d[0] for d in cur.description]
        rows = cur.fetchall()

    with out.open("w", newline="", encoding="utf-8") as f:
        writer = csv.writer(f)
        writer.writerow(headers + ["progress_pct"])
        for r in rows:
            total, done = r[3], r[4]
            pct = round(done * 100 / total) if total else 0
            writer.writerow(list(r) + [pct])

    print(f"Wrote {len(rows)} rows to {out.resolve()}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
