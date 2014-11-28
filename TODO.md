##Course Builder TODO List

###Project Objective
The student enters his Program (Software, electical, etc.), the courses he has taken so far and on/off pattern.

The program returns a list of all the course combinations that can be taken and are available.

###Questions

- Why do we need list of completed courses in View 1?
- Why do we need both PHP and Java? [Two versions are required : one in Java and one in HTML (and javascript)]?

Note: Abstract all database queries

###Database

1. [x] Create general schema in MySQL
2. [x] Handle course offerings (Labs, Lectures, capacity)
3. [x] Handle course sections
4. [x] Store Electives


###Front End

1. [x] Create input page (View 1)
    Input fields: Program, on/off pattern, list of completed courses
    - NOTE: Need to replace hard-coded dropdown menu with data from database
    - When a program has been selected, replace the combo box value
    - Right now the page is reloaded upon selection, need to change this.
    - Add JavaScript so that the courses are auto-selected when user chooses on pattern
2. [x] Display user input (boxes)
3. [x] Add Submission Button
4. [x] Create schedule page (View 2)
5. [x] This View asks the student to select the electives he wants to take and to confirm that he will register in the proposed courses.


###Back End

1. [x] Setup connection between PHP and MySQL
2. [x] Write functions to query database (factor queries)
3. [x] Write a function that determines what time of year it is to determine
    - If June-September, look at fall term (courses)
    - If December-January, look at winter  term (courses) 
    - The application will focus on one term at a time
4. [x] Determine the list of courses the student can take based on the courses already completed (need to look at course availabilty, prerequisites)
5. [x] Need to look at what program the Student is in to generate schedule
6. [x] For the electives, identify the courses the student can take, based on prerequisites, availability and schedule
7. [ ] From the student’s confirmation, update the database of courses change the number of available seats. 
8. [ ] Handle if two people try to book the same course at the same time.
    - if class full, return false message and refresh list of schedules
