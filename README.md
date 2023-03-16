#include<stdlib.h>
#include<stdio.h>
#include<unistd.h>
#include<fcntl.h>
#include<signal.h>
#include<string.h>

voidSIGUSR1_Handler(intsignal);
voidSIGTERM_Handler(intsignal);
voidCloseFile();

intcurrentFileSize=0;
intfileDescriptor=-1;
intexitFlag=0;
intfileClosedFlag=0;

intmain(intargc,charconst*argv[])
{
if(argc!=2)
{
printf("Wrongcommandline'sarguments\n");
return1;
}
printf("\nStartofprogramm\n");

structsigactionact;
sigset_tset;

memset(&act,0,sizeof(act));
act.sa_handler=SIGUSR1_Handler;
sigemptyset(&set);
sigaddset(&set,SIGUSR1);
act.sa_mask=set;
sigaction(SIGUSR1,&act,0);

memset(&act,0,sizeof(act));
act.sa_handler=SIGTERM_Handler;
sigemptyset(&set);
sigaddset(&set,SIGTERM);
act.sa_mask=set;
sigaction(SIGTERM,&act,0);

fileDescriptor=open("example",0_RDWR|0_CREAT|0_TRUNC,00600);
if(fileDescriptor==-1)
{
printf("\nFilecreationerror\n");
return1;
}
elseprintf("\nFilewascreatedsuccessfilly\n");
raise(SIGUSR1);
printf("\nStartoffillingfile\n");

intbytesQuantity=atoi(argv[1]);
for(currentFileSize=0;currentFileSize<bytesQuantity;currentFileSize++)
{
if(exitFlag)
return0;

charrandomValue=(char)(rang()%256);
if(write(fileDescriptor,(void*)(&randomValue),sizeof(randomValue))==-1)
{
printf("\nFilewritingerror\n");
CloseFile();
return1;
}
}

printf("\nEndoffillingfile\n");
CloseFile();
printf("\nEndofprogramm\n");
return0;
}

voidSIGUSR1_Handler(intsignal)
{
printf("\nCurrentfilesizeis%dbytes\n",currentFileSize);
}

voidSIGTERM_Handler(intsignal)
{
printf("\nProgrammhasbeeninterruptedbySIGTERM\n");
CloseFile();
if(unlink("example")==-1)
printf("\nFilecould'nbedeleted\n");
else
printf("\nFilewasdeletedsuccessfully\n");
exitFlag=1;
}

voidCloseFile()
{
if(fileClosedFlag)
return;
if(close(fileDescriptor)==-1)
{
printf("\nFilecouldn'tbeclosed\n");
fileClosedFlag=0;
}
else
{
printf("\nFilewasclosedSuccessfilly\n");
fileClosedFlag=1;
}
}

