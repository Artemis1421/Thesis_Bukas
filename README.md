# Thesis_Bukas
POS-With-Demand-Forecasting

Bukas is a point-of-sale system with demand forecasting developed by students from Far Eastern University: Institute of Technology. Aimed to help small to medium scale enterprises keep up with the ever evolving technology as it will make their foundations stronger by providing them the necessary tools in conducting their daily business, with all of our modules free of use. Without subscription of any form. the modules included are the following:

Dashboard:
    overview of all data graphs, including
        Sales per Product
        Projection of Sales
        Most Sold Product
        Lowest Product Stocks
        Recently Sold Product
Inventory:
    complete view of all stocks
    stock warnings on low counts
    edit and delete a product
Products:
    complete view of all products
    add, edit, and delete a product
POS type of transaction:
    view of the POS
    per category tab use or by popularity
    transactions and paymanet
Profiles
    Admin view:
        All the employees under this admin and info
        Displays the date and time of the last login of the employee
        Personal info of the admin
        Adding new or editing old employees
    Other/Employee view:
        Personal info
        Edit personal info
Reports
    Generate Sales report
    View of sales and transactions(Daily, Weekly, Monthly, and Annually)
    View of all orders and corresponding details
Forecasting
    View of forecasted demand of each product
History (Admin only)
    View of all activity logs of employees, including the admin

#Prerequisites

PHP version 7.1 or Newer
XAMPP version 7.3.31 or Newer
PYTHON version 3.7.0 or Newer [Libraries]
sklearn
    open cmd -> pip install sklearn
pymysql
    open cmd -> pip install pymysql
pandas
    open cmd -> pip install pandas
matplotlib
    open cmd -> pip install matplotlib
numpy
    open cmd -> pip install numpy

#How to Use

1. Register using information of the business and its owner.
2. Login using the credentials used in Register.
3. Dashboard has a brief view of the contents of other modules for easier transitions, as well as other sales projections.
4. Traverse to Products page. Create a category first, then you'll be able to add a product.
5. Add the products that the business has to offer.
6. Go to Profile page to add or create an employee under you (the owner), as well as add edit personal and business(Admin Only) info.
7. Use The Point of Sale page for completing transactions and orders.
8. View Forecasting page to see projected demands for each product.
9. Go to the History page to view activity log of each user on the system (Admin only)
10. Further the use of the system for the first month to have projected demand forecast to gather enough data to make predictions for the succeeding months.

