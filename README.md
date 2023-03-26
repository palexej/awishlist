/*Ïîëÿ äàííûõ: ôàìèëèÿ ñòóäåíòà è îöåíêè ïî ôèçèêå, ìàòåìàòèêå è
èíôîðìàòèêå. Âûâåñòè êîëè÷åñòâî äâîåê ïî êàæäîìó èç ïðåäìåòîâ è âûâåñòè
ñïèñîê ñòóäåíòîâ, èìåþùèõ äâîéêè õîòÿ áû ïî îäíîìó ïðåäìåòó.*/

#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
#include <Windows.h>//äëÿ çàïèñè êèðèëëèöû â ôàéë

const char DEFAULT_FILE[]="pr1.bin";
struct student
{
    char surname[30];
    char name[30];
    int studyCourse;

    struct marks
    {
        int mathMark;
        int physichsMark;
        int informaticsMath;
    } studentMarks;


    int isHaveScholarship;
};

typedef struct student DataType;

struct node
{
    DataType data;
    struct node * next;//îïðåäåëÿåò óçåë îäíîñâÿçíîãî ëèíåéíîãî ñïèñêà, ïîëå óêàçàòåëÿ
};

typedef struct node * list;//ïåðåèìåíîâàíèÿ ñòðóêòóðû óçëà â ñïèñîê

DataType input_student (void);
list read_file (char * );
list new_node (list, DataType);
int write_file (char *, list);
void delete_list (list);

void show (list);
void search (list begin);
list delete_node (list);

int checkValue(int,int);
void print_data (struct student);

list add_end (list, DataType);
list find_last (list);


int main (int argc, char *argv[])
{
    SetConsoleCP(1251);// óñòàíîâêà êîäîâîé ñòðàíèöû win-cp 1251 â ïîòîê ââîäà
    SetConsoleOutputCP(1251); // óñòàíîâêà êîäîâîé ñòðàíèöû win-cp 1251 â ïîòîê âûâîäà
    setlocale(LC_ALL, "Rus");
    char file[50];
    char menu;
    list student = NULL;

    if(argc!=2)//ïåðåäà÷à ïàðàìåòðîâ â ôóíêöèþ main ÷åðåç êîìàíäíóþ ñòðîêó
    {
        printf("Èìÿ ôàéëà íå óêàçàíî â ïàðàìåòðàõ çàïóñêà. Áóäåò èñïîëüçîâàíî èìÿ ôàéëà ïî óìîë÷àíèþ: pr1.bin  ");
        strcpy(file,DEFAULT_FILE);
    }
    else
    {
        puts ("Èìÿ ôàéëà ñîîîòâåñòñòâóåò ôàéëó, óêàçàííîìó â ïàðàìåòðàõ çàïóñêà");
        strcpy(file,argv[1]);//åñëè áûëî ïåðåäàíî èìÿ ôàéëà ÷åðåç ïàðàìåòðû çàïóñêà, òî èìÿ ôàéëà = èìåíè ôàéëà ÷åðåç ïàðàìåòðû çàïóñêà
    }

    getchar();

    student = read_file (file);
    do
    {
        //system ("CLS");
        puts("1. Äîáàâèòü ñòóäåíòà");
        puts("2. Ïðîñìîòðåòü ñïèñîê ñòóäåíòîâ");
        puts("3. Ïîèñê ñòóäåíòîâ ïî îöåíêàì");
        puts("4. Óäàëåíèå ïîñëåäíåãî ñòóäåíòà");
        puts("5. Âûõîä èç ïðîãðàììû");
        menu = getchar();
        getchar();
        switch (menu)
        {

        case '1':
            student = add_end (student, input_student());
            break;
        case '2':
            show (student);
            break;
        case '3':
            search (student);
            break;
        case '4':
            student = delete_node (student);
        }
    }
    while (menu!='5');
    if (write_file (file, student))
        puts ("Ôàéë óñïåøíî ñîõðàí¸í ");
    else
        puts ("Îøèáêà ñîõðàíåíèÿ ôàéëà ");
    delete_list (student);
    return 0;
}

DataType input_student (void)
{

    //system("cls");

    DataType student;
    puts ("Ôàìèëèÿ ñòóäåíòà: ");
    gets (student.surname);

    puts ("Èìÿ ñòóäåíòà: ");
    gets (student.name);

    puts ("Òåêóùèé êóðñ îáó÷åíèÿ ñòóäåíòà(âñåãî 4 êóðñà): ");
    student.studyCourse=checkValue(1,4);

    // student.studentMarks.informaticsMath=;

    puts ("Íàëè÷èå ñòèïåíäèè(1-äà, 0-íåò): ");
    student.isHaveScholarship=checkValue(0,1);

    puts ("Îöåíêà ïî ìàòåìàòèêå: ");
    student.studentMarks.mathMark=checkValue(2,5);

    puts ("Îöåíêà ïî èíôîðìàòèêå");
    student.studentMarks.informaticsMath=checkValue(2,5);

    puts ("Îöåíêà ïî ôèçèêå");
    student.studentMarks.physichsMark=checkValue(2,5);

    getchar();
    return student;
}


list new_node (list begin, DataType student)
{
    list temp = (list) malloc (sizeof(struct node));//âûäåëåíèå ïàìÿòè ïîä ñòðóêòóðó
    temp->data = student;
    temp->next = begin;
    return temp;

}

list read_file (char * filename)
{
    FILE * f;
    DataType student;
    list begin=NULL, cur;
    if ((f = fopen (filename, "rb")) == NULL)
    {
        perror ("Îøèáêà îòêðûòèÿ ôàéëà");
        getchar();
        return begin;
    }
    if (fread(&student, sizeof(student), 1, f))
        begin = new_node (NULL, student);
    else
        return NULL;
    cur = begin;
    while (fread(&student, sizeof(student), 1, f))
    {
        cur->next = new_node (NULL, student);//óêàçàòåëü ê ïîëÿì ñòðóêòóðû
        cur = cur->next;
    }
    fclose(f);
    return begin;
}

void delete_list (list begin)//îñâîáîæäåíèå ïàìÿòè äëÿ âñåõ óçëîâ ïîñëåäîâàòåëüíî
{
    list temp = begin;
    while (temp)
    {
        begin = temp->next;
        free (temp);
        temp = begin;
    }
}

int write_file (char * filename, list begin)
{
    FILE * f;
    if ((f = fopen (filename, "wb")) == NULL)
    {
        perror ("Îøèáêà ñîçäàíèÿ ôàéëà");
        getchar();
        return 0;
    }
    while (begin)
    {
        if (fwrite (&begin->data, sizeof(DataType), 1, f))
            begin = begin->next;
        else
            return 0;
    }
    return 1;
}

void print_data (struct student student)
{

    printf ("| %-30s | %-25s | %-13d | %-17d |%-20d | %-21d | %-16d |\n", student.surname,
            student.name, student.studyCourse, student.isHaveScholarship,
            student.studentMarks.mathMark,student.studentMarks.informaticsMath, student.studentMarks.physichsMark);

}

void show (list cur)
{
    int k=0;
   // system ("CLS");
    if (cur == NULL)
    {
        puts ("Ñïèñîê ïóñò");
        getchar();
        return;
    }
    puts ("|  N |          Ôàìèëèÿ               |            Èìÿ            | Êóðñ îáó÷åíèÿ | Íàëè÷èå ñòèïåíäèè | Îöåíêà ïî ìàòåìàòèêå| Îöåíêà ïî èíôîðìàòèêå | Îöåíêà ïî ôèçèêå |");
    puts ("------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
    while (cur)
    {
        printf ("|%3d | %-30s | %-25s | %-13d | %-17d |%-20d | %-21d | %-16d |\n", ++k, cur->data.surname,
                cur->data.name, cur->data.studyCourse, cur->data.isHaveScholarship,
                cur->data.studentMarks.mathMark, cur->data.studentMarks.informaticsMath, cur->data.studentMarks.physichsMark);//ìåíÿåòñÿ âûâîä
        cur = cur->next;
    }
    puts ("-------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
    getchar();
}

void search (list cur)
{
    int k=-1;
    char studentSurname[30];
    DataType student;
   // system ("CLS");
    if (cur == NULL)
    {
        puts ("Ñïèñîê ïóñò");
        getchar();
        return;
    }

    int countMathMark=0, countInformaticsMark=0, countPhysicMark=0, findingMark;

    printf("Ââåäèòå îöåíêó îò 2 äî 5 äëÿ ïîèñêà ñòóäåíòîâ ñ òàêèìè îöåíêàìè: ");
    scanf("%d",&findingMark);

    puts ("|          Ôàìèëèÿ               |            Èìÿ            | Êóðñ îáó÷åíèÿ | Íàëè÷èå ñòèïåíäèè | Îöåíêà ïî ìàòåìàòèêå| Îöåíêà ïî èíôîðìàòèêå | Îöåíêà ïî ôèçèêå |");
    puts ("------------------------------------------------------------------------------------------------------------------------------------------------------------------------");

    while (cur)
    {
        k++;
        if (cur->data.studentMarks.mathMark==findingMark)//ñðàâíåíèå èñêîìîé îöåíêè è îöåíêè èç óçëà ñïèñêà
        {
            countMathMark++;
        }

        if (cur->data.studentMarks.informaticsMath==findingMark)
        {
            countInformaticsMark++;
        }

        if (cur->data.studentMarks.physichsMark==findingMark)
        {
            countPhysicMark++;
        }

        //åñëè îöåíêà íàéäåíà, òî ïå÷àòàåòñÿ îäíà ñòðî÷êà ñ äàííûìè ñòóäåíòà
        if(cur->data.studentMarks.informaticsMath==findingMark ||cur->data.studentMarks.mathMark==findingMark || cur->data.studentMarks.physichsMark==findingMark)
        {
            student = cur->data;
            print_data (student);

        }

        cur = cur->next;

    }

    if (countPhysicMark == 0 && countMathMark == 0 && countInformaticsMark == 0)
    {
       // system("CLS");
        puts ("Ñòóäåíòû ñ èñêîìûìè îöåíêàìè îòñóòñòñâóþò");

    }

    else
    {
        puts ("-------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
        printf("\nÊîëè÷åñòâî îöåíîê '%d' ïî ìàòåìàòèêå %d",findingMark,countMathMark);
        printf("\nÊîëè÷åñòâî îöåíîê  '%d' ïî èíôîðìàòèêå: %d",findingMark,countInformaticsMark);
        printf("\nÊîëè÷åñòâî îöåíîê  '%d' ïî ôèçèêå: %d\n",findingMark,countPhysicMark);
    }

    getchar();
    //system("pause");
}

list delete_node (list begin) /*óäàëåíèå ïåðâîãî ýëåìåíòà*/
{

    list thisNode=NULL, prevNode=NULL;  //òåêóùèé è ïðåäûäóùèé óçåë
    //Ïîëó÷èëè NULL
    if (begin)
    {

        thisNode = begin;
        while (thisNode->next)//ïîêà ñïèñîê íå êîí÷èëñÿ
        {
            prevNode = thisNode;//ïðåäûäóùåìó - òåêóùèé, à ïðåäûäóùåìó - ñëåäóþùèé
            thisNode = thisNode->next;
        }

        if (prevNode == NULL)//åñëè â ñïèñêå åñòü òîëüêî 1 êîíå÷íûé ýëåìåíò è íåò ïðåäûäóùåãî
        {
            free(begin);
            begin = NULL;//îñâîáîæäàåì ïàìÿòü è îáíóëÿåì ñïèñîê
            puts ("Ïåðâûé ýëåìåíò ñïèñêà óäàë¸í");
        }
        else
        {
            free(thisNode->next);//îñâîáîæäàåì ïàìÿòü
            prevNode->next = NULL;//óáèðàåì óêàçàòåëü ñ ïðåäûäóùåãî ýë-òà ñïèñêà íà ïîñëåäíèé óäàë¸ííûé ýëåìåíò
            puts ("Ïîñëåäíèé ýëåìåíò ñïèñêà óäàë¸í");
        }
    }
    //Ñïèñîê ïóñò

    getchar();
    return begin;

}

list add_end (list begin, DataType dataType)
{

    list lastNode=NULL;
    if (begin == NULL) //åñëè ñïèñîê ïóñò
        return new_node (NULL, dataType); //ñîçäàåì óçåë è âîçâðàùàåì åãî àäðåñ

    lastNode=begin;//ïðèñâàèâàåì íà÷àëî ñïèñêà â ïåðåìåííóþ, ÷òîáû íå ïîòåðÿòü ãîëîâó
    while (lastNode->next)//ïîêà îò íà÷àëà ñïèñêà åñòü äðóãèå
    {
        lastNode = lastNode->next;//ïåðåáèðàåì äî êîíöà
    }

    lastNode->next=new_node(NULL,dataType);//ñîçäàíèå óçëà ïîñëå ïîñëåäíåãî ýëåìåíòà ñïèñêà



    return begin;//âîçâðàò íà÷àëà ñïèñêà
}

int checkValue( int bottomLine,int upperLine) //ôóíêöèÿ ïðîâåðêè çíà÷åèé èç äèàïàçîíà
{
    int checkingValue;
    //scanf ("%d", &checkingValue);

    while(1)
    {
        scanf ("%d", &checkingValue);
        if(checkingValue<bottomLine|| checkingValue>upperLine)
        {
            printf("Çíà÷åíèå ââåäåíî íåâåðíî, ïîâòîðèòå ïîïûòêó.\n");

        }
        else
        {
            return checkingValue;
        }

    }
}
