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

void SIGUSR1_Handler(int signal)
{
printf("Увеличение значения файла на 1\n");
int myNumber;
FILE *readFile = fopen(FILENAME, "r");
if (readFile != NULL)
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
fprintf (readFile,"%d",0);
fclose (readFile);
}
int main()
{
pid_t pid;
pid = fork();

if (pid < 0 || setsid() < 0)
exit(EXIT_FAILURE);
if (pid > 0)
exit(EXIT_SUCCESS);

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
while(!feof(readSignalFile))
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

pid = fork();
if (pid < 0)
exit(EXIT_FAILURE);
if (pid > 0)
exit(EXIT_SUCCESS);

umask(0);

chdir("/");
int x;
for (x = sysconf(_SC_OPEN_MAX); x>=0; x--)
{
close (x);
}
}
