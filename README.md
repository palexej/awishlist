
#include <stdio.h>
#include <stdlib.h>
#include <locale.h>

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
struct node * next;
};

typedef struct node * list;

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
SetConsoleCP(1251);
SetConsoleOutputCP(1251);

char file[50];
char menu;
list student = NULL;

if(argc!=2)
{
printf("Имя файла не указано в параметрах запуска. Будет использовано имя файла по умолчанию: pr1.bin ");
strcpy(file,DEFAULT_FILE);
}
else
{
puts ("Имя файла сооотвестствует файлу, указанному в параметрах запуска");
strcpy(file,argv[1]);
}

getchar();

student = read_file (file);
do
{
system ("CLS");
puts("1. Добавить студента");
puts("2. Просмотреть список студентов");
puts("3. Поиск студентов по оценкам");
puts("4. Удаление последнего студента");
puts("5. Выход из программы");
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
puts ("Файл успешно сохранён ");
else
puts ("Ошибка сохранения файла ");
delete_list (student);
return 0;
}

DataType input_student (void)
{

system("cls");

DataType student;
puts ("Фамилия студента: ");
gets (student.surname);

puts ("Имя студента: ");
gets (student.name);

puts ("Текущий курс обучения студента(всего 4 курса): ");
student.studyCourse=checkValue(1,4);



puts ("Наличие стипендии(1-да, 0-нет): ");
student.isHaveScholarship=checkValue(0,1);

puts ("Оценка по математике: ");
student.studentMarks.mathMark=checkValue(2,5);

puts ("Оценка по информатике");
student.studentMarks.informaticsMath=checkValue(2,5);

puts ("Оценка по физике");
student.studentMarks.physichsMark=checkValue(2,5);

getchar();
return student;
}


list new_node (list begin, DataType student)
{
list temp = (list) malloc (sizeof(struct node));
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
perror ("Ошибка открытия файла");
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
cur->next = new_node (NULL, student);
cur = cur->next;
}
fclose(f);
return begin;
}

void delete_list (list begin)
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
perror ("Ошибка создания файла");
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
system ("CLS");
if (cur == NULL)
{
puts ("Список пуст");
getchar();
return;
}
puts ("| N | Фамилия | Имя | Курс обучения | Наличие стипендии | Оценка по математике| Оценка по информатике | Оценка по физике |");
puts ("------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
while (cur)
{
printf ("|%3d | %-30s | %-25s | %-13d | %-17d |%-20d | %-21d | %-16d |\n", ++k, cur->data.surname,
cur->data.name, cur->data.studyCourse, cur->data.isHaveScholarship,
cur->data.studentMarks.mathMark, cur->data.studentMarks.informaticsMath, cur->data.studentMarks.physichsMark);

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
system ("CLS");
if (cur == NULL)
{
puts ("Список пуст");
getchar();
return;
}

int countMathMark=0, countInformaticsMark=0, countPhysicMark=0, findingMark;

printf("Введите оценку от 2 до 5 для поиска студентов с такими оценками: ");
scanf("%d",&findingMark);

puts ("| Фамилия | Имя | Курс обучения | Наличие стипендии | Оценка по математике| Оценка по информатике | Оценка по физике |");
puts ("------------------------------------------------------------------------------------------------------------------------------------------------------------------------");

while (cur)
{
k++;
if (cur->data.studentMarks.mathMark==findingMark)
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


if(cur->data.studentMarks.informaticsMath==findingMark ||cur->data.studentMarks.mathMark==findingMark || cur->data.studentMarks.physichsMark==findingMark)
{
student = cur->data;
print_data (student);

}

cur = cur->next;

}

if (countPhysicMark == 0 && countMathMark == 0 && countInformaticsMark == 0)
{
system("CLS");
puts ("Студенты с искомыми оценками отсутстсвуют");

}

else
{
puts ("-------------------------------------------------------------------------------------------------------------------------------------------------------------------------");
printf("\nКоличество оценок '%d' по математике %d",findingMark,countMathMark);
printf("\nКоличество оценок '%d' по информатике: %d",findingMark,countInformaticsMark);
printf("\nКоличество оценок '%d' по физике: %d\n",findingMark,countPhysicMark);
}

getchar();
system("pause");
}

list delete_node (list begin)
{

list thisNode=NULL, prevNode=NULL;
if (begin)
{

thisNode = begin;
while (thisNode->next)
{
prevNode = thisNode;
thisNode = thisNode->next;
}

if (prevNode == NULL)
{
free(begin);
begin = NULL;
puts ("Первый элемент списка удалён");
}
else
{
free(thisNode->next);
prevNode->next = NULL;
puts ("Последний элемент списка удалён");
}
}


getchar();
return begin;

}

list add_end (list begin, DataType dataType)
{

list lastNode=NULL;
if (begin == NULL)
return new_node (NULL, dataType);

lastNode=begin;
while (lastNode->next)
{
lastNode = lastNode->next;
}

lastNode->next=new_node(NULL,dataType);



return begin;
}

int checkValue( int bottomLine,int upperLine)
{
int checkingValue;

while(1)
{
scanf ("%d", &checkingValue);
if(checkingValue<bottomLine|| checkingValue>upperLine)
{
printf("Значение введено неверно, повторите попытку.\n");

}
else
{
return checkingValue;
}

}
}
