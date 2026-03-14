@echo off
cd /d "C:\xampp\htdocs\aaapanel007\cron-datosabiertos-mef-python-csv"
python -c "import sys; sys.path.append('.'); import etl_siaf_scheduler; etl_siaf_scheduler.run_etl()"