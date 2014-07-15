bash command:
   php -e compare.php 'month/filename_for_list_1.txt' 'month/filename_for_list_2.txt'

   example: php -e compare.php 'jul/A_FULL_DB_020714.txt' 'jul/B_FULL_DB_020714.txt'

For testing 
   bash command:
 		php -e compare.php 'jul/a.txt' 'jul/b.txt'

   a.txt = 1,2,2,  4,6
   b.txt = 1,    3,4,6,6

   results are Crossover = 1,4,6
   results are A = 2
   results are B = 3
