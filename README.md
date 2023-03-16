#include <stdio.h>
#include <stdlib.h>
#include <signal.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#include <fcntl.h>
#include <string.h>
char FILENAME[]="file.log";
FILE *readFile;
//удаление файла и завершение работы программы
void SIGTERM_Handler(int signal)
{
if (-1 == remove (FILENAME))
printf ("Ошибка удаления файла\n");
else
{
printf ("Выполнено удаление файла\n");
printf("Завершение работы\n");
}
}
//чтение значения из файла, увеличение на 1 и обратная запись в файл
void SIGUSR1_Handler(int signal)//считывание файла
{
printf("Увеличение значения файла на 1\n");
int myNumber;
FILE *readFile = fopen(FILENAME, "r");
if (readFile != NULL)//если файла нет, ничего не делаем
{
fscanf(readFile, "%d", &myNumber);
myNumber += 1;
printf("\tЗаписано значение: %d\n",myNumber);
FILE *writeFile = fopen(FILENAME, "w");
fprintf(writeFile,"%d", myNumber);
fclose(writeFile);
fclose(readFile);
}
else
{
printf("Файл не существует, значение не записано\n");
}
}
void SIGHUP_Handler(int signal)
{
printf("Создание файла и запись в него числа 0\n");
readFile=fopen (FILENAME,"w");
fprintf (readFile,"%d",0);//Запись в файл
fclose (readFile);
}
int main()
{
pid_t pid;
pid = fork();
//проверка на верные значения pid
if (pid < 0 || setsid() < 0)
exit(EXIT_FAILURE);
if (pid > 0)
exit(EXIT_SUCCESS);
//добавление прослушивания каждого из событий
struct sigaction act;
sigset_t set;
memset(&act,0,sizeof(act));
act.sa_handler = SIGHUP_Handler;
sigemptyset(&set);
sigaddset(&set, SIGHUP);
act.sa_mask = set;
sigaction(SIGHUP, &act, 0);
memset(&act,0,sizeof(act));
act.sa_handler = SIGUSR1_Handler;
sigemptyset(&set);
sigaddset(&set, SIGUSR1);
act.sa_mask = set;
sigaction(SIGUSR1, &act, 0);
memset(&act,0,sizeof(act));
act.sa_handler = SIGTERM_Handler;
sigemptyset(&set);
sigaddset(&set, SIGTERM);
act.sa_mask = set;
sigaction(SIGTERM, &act, 0);
FILE *readSignalFile = fopen("signals.txt","r");
char buf[255];
while(!feof(readSignalFile))//чтение сигналов из файла
{
char * myString=fgets(buf,sizeof(buf),readSignalFile);
printf("Считан сигнал %s",myString);
if(strcmp(myString,"SIGHUP\n")==0)
{
raise(SIGHUP);
}
if(strcmp(myString,"SIGUSR1\n")==0)
{
raise(SIGUSR1);
}
if(strcmp(myString,"SIGTERM\n")==0)
{
raise(SIGTERM);
return 0;
}
}
fclose(readSignalFile);
// raise(SIGUSR1);
// raise(SIGHUP);
// raise(SIGUSR1);
// raise(SIGUSR1);
// raise(SIGUSR1);
// raise(SIGTERM);
//повторное выполнение описанных выше действий
pid = fork();
if (pid < 0)
exit(EXIT_FAILURE);
if (pid > 0)
exit(EXIT_SUCCESS);
//смена прав доступа на разрешение выполнения всего
umask(0);
//смена директории для демона
chdir("/");
int x;
for (x = sysconf(_SC_OPEN_MAX); x>=0; x--)
{
close (x);
}
}
