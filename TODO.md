##Course Builder TODO List

###Project Objective
The student enters his Program (Software, electical, etc.), the courses he has taken so far and on/off pattern.

The program returns a list of all the course combinations that can be taken and are available.

###Questions

- Why do we need list of completed courses in View 1?
- Why do we need both PHP and Java? [Two versions are required : one in Java and one in HTML (and javascript)]?

Note: Abstract all database queries

###Database

1. Create general schema in MySQL
2. Handle course offerings (Labs, Lectures, capacity)
3. Handle course sections
4. Store Electives


###Front End

1. Create input page (View 1)
    Input fields: Program, on/off pattern, list of completed courses
2. Display user input (list)
3. Allow user to input courses as text (need to parse text)
4. Add auto-completion for text
5. Add Submission Button
6. Assume courses and electives are seperated by the user 
   (maybe 2 input fields)
7. Create schedule page (View 2)
8. This View asks the student to select the electives he wants to take and to confirm that he will register in the proposed courses.


###Back End

1. Setup connection between PHP and MySQL
2. Write functions to query database (factor queries)
3. Write a function that determines what time of year it is to determine
    - If June-September, look at fall term (courses)
    - If December-January, look at winter  term (courses) 
    - The application will focus on one term at a time
4. Determine the list of courses the student can take based on the courses already completed (need to look at course availabilty, prerequisites)
5. Need to look at what program the Student is in to generate schedule
6. For the electives, identify the courses the student can take, based on prerequisites, availability and schedule
7. From the student’s confirmation, update the database of courses change the number of available seats. 
8. Handle if two people try to book the same course at the same time.
    - if class full, return false message and refresh list of schedules
