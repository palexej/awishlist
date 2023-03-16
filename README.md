#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <fcntl.h>
#include <signal.h>
#include <string.h>

void SIGUSR1_Handler(int signal);
void SIGTERM_Handler(int signal);
void CloseFile();

int currentFileSize = 0;
int fileDescriptor = -1;
int exitFlag = 0;
int fileClosedFlag = 0;

int main(int argc, char const *argv[])
{
    if (argc != 2)
    {
        printf("Wrong command line's arguments\n");
        return 1;
    }
    printf("\nStart of programm\n");

    struct sigaction act'
    sigset_t set;

    memset(&act,0,sizeof(act));
    act.sa_handler = SIGUSR1_Handler;
    sigemptyset(&set);
    sigaddset(&set, SIGUSR1);
    act.sa_mask = set;
    sigaction(SIGUSR1, &act, 0);

    memset(&act, 0, sizeof(act));
    act.sa_handler = SIGTERM_Handler;
    sigemptyset(&set);
    sigaddset(&set, SIGTERM);
    act.sa_mask = set;
    sigaction(SIGTERM, &act, 0);

    fileDescriptor = open("example", 0_RDWR | 0_CREAT | 0_TRUNC, 00600);
    if (fileDescriptor == -1)
    {
        printf("\nFile creation error\n");
        return 1;
    }
    else printf("\nFile was created successfilly\n");
    raise(SIGUSR1);
    printf("\nStart of filling file\n");

    int bytesQuantity = atoi(argv[1]);
    for (currentFileSize = 0; currentFileSize < bytesQuantity; currentFileSize++)
    {
        if(exitFlag)
            return 0;

        char randomValue = (char)(rang() % 256);
        if (write(fileDescriptor, (void*)(&randomValue), sizeof(randomValue)) == -1)
        {
            printf("\nFile writing error\n");
            CloseFile();
            return 1;
        }
    }

    printf("\nEnd of filling file\n");
    CloseFile();
    printf("\nEnd of programm\n");
    return 0;
}

void SIGUSR1_Handler(int signal)
{
    printf("\n Current file size is %d bytes\n", currentFileSize);
}

void SIGTERM_Handler(int signal)
{
    printf("\nProgramm has been interrupted by SIGTERM\n");
    CloseFile();
    if (unlink("example") == -1)
        printf("\nFile could'n be deleted\n");
    else
        printf("\nFile was deleted successfully\n");
    exitFlag = 1;
}

void CloseFile()
{
    if (fileClosedFlag)
        return;
    if (close(fileDescriptor) == -1)
    {
        printf("\nFile couldn't be closed\n");
        fileClosedFlag = 0;
    }
    else
    {
        printf("\nFile was closed Successfilly\n");
        fileClosedFlag = 1;
    }
}

