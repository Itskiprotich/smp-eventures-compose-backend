<div class="app-sidebar__user"><img style="width: 50px; height: 50px;" class="app-sidebar__user-avatar" src="{{ asset('images/smp.jpg') }}" alt="User Image">

    <div>
        <p class="app-sidebar__user-name">{{{Auth::user()->name}}}</p>
        <p class="app-sidebar__user-designation">{{{Auth::user()->email}}}</p>
    </div>
</div>
<ul class="app-menu">
    <li><a class="app-menu__item active" href="/home"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>


    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Borrowers</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/customer/add"><i class="icon fa fa-circle-o"></i> Add</a>
            </li>
            <li><a class="treeview-item" href="/customer/pending"><i class="icon fa fa-circle-o"></i>
                    Pending</a></li>
            <li><a class="treeview-item" href="/customer/approved"><i class="icon fa fa-circle-o"></i>
                    Approved</a></li>

        </ul>
    </li>

    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-money"></i><span class="app-menu__label">Loans</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/loans"><i class="icon fa fa-circle-o"></i> Pending</a></li>
            <li><a class="treeview-item" href="/loans/approved"><i class="icon fa fa-circle-o"></i>
                    Approved</a></li>
            <li><a class="treeview-item" href="/loans/disbursed"><i class="icon fa fa-circle-o"></i>
                    Disbursed</a></li>
            <li><a class="treeview-item" href="/loans/paid"><i class="icon fa fa-circle-o"></i> Paid</a>
            </li>
            <li><a class="treeview-item" href="/loans/today"><i class="icon fa fa-circle-o"></i> Due Today</a>
            </li>
            <li><a class="treeview-item" href="/loans/rejected"><i class="icon fa fa-circle-o"></i>
                    Rejected</a></li>
            <li><a class="treeview-item" href="/loans/overdue"><i class="icon fa fa-circle-o"></i>
                    Overdue</a></li>

        </ul>
    </li>
    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Receipting</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/payments"><i class="icon fa fa-circle-o"></i>Payments</a>
            </li>

        </ul>
    </li>
    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-shopping-basket"></i><span class="app-menu__label">Savings</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/savings"><i class="icon fa fa-circle-o"></i> View</a></li>
            <li><a class="treeview-item" href="/savings/pwithdrawals"><i class="icon fa fa-circle-o"></i>
                    Pending Withdrawals</a></li>

            <li><a class="treeview-item" href="/savings/awithdrawals"><i class="icon fa fa-circle-o"></i>
                    Approved Withdrawals</a></li>

        </ul>
    </li>
    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-university"></i><span class="app-menu__label">Investment</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/investment"><i class="icon fa fa-circle-o"></i> View</a>
            </li>
            <li><a class="treeview-item" href="/investment/pending"><i class="icon fa fa-circle-o"></i>
                    Pending Withdrawals</a></li>
            <li><a class="treeview-item" href="/investment/approved"><i class="icon fa fa-circle-o"></i>
                    Approved Withdrawals</a></li>

        </ul>
    </li>

    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-smile-o"></i><span class="app-menu__label">Welfare</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/welfare"><i class="icon fa fa-circle-o"></i> Members</a>
            </li>
        </ul>
    </li>
    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-superpowers"></i><span class="app-menu__label">Shares</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/shares"><i class="icon fa fa-circle-o"></i> Members</a></li>
        </ul>
    </li>
    <!-- <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-book"></i><span class="app-menu__label">Accounting</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/reports"><i class="icon fa fa-circle-o"></i> Accounts </a>
            </li>
            <li><a class="treeview-item" href="/reports/income"><i class="icon fa fa-circle-o"></i> Income
                    Statement</a></li>
            <li><a class="treeview-item" href="/reports/consolidated"><i class="icon fa fa-circle-o"></i>
                    Consolidated</a></li>
            <li><a class="treeview-item" href="/reports/monthly"><i class="icon fa fa-circle-o"></i> Monthly
                    Cashflow</a></li>
            <li><a class="treeview-item" href="/reports/accumulated"><i class="icon fa fa-circle-o"></i>
                    Accumulated Cashflow</a></li>

        </ul>
    </li> -->
    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">Admins</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/admin"><i class="icon fa fa-circle-o"></i> View</a></li>
            <li><a class="treeview-item" href="/admin/float"><i class="icon fa fa-circle-o"></i> Float</a>
            </li>


        </ul>
    </li>
    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-table"></i><span class="app-menu__label">Billing</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/billing"><i class="icon fa fa-circle-o"></i> View</a></li>


        </ul>
    </li>
    <!-- <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-graduation-cap"></i><span class="app-menu__label">Learning Center</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="/learning"><i class="icon fa fa-circle-o"></i> Dashboard</a></li>
            <li><a class="treeview-item" href="/learning/students"><i class="icon fa fa-circle-o"></i> Students</a></li>
            <li><a class="treeview-item" href="/learning/instructors"><i class="icon fa fa-circle-o"></i> Instructors</a></li>
            <li><a class="treeview-item" href="/learning/courses"><i class="icon fa fa-circle-o"></i> Courses</a></li>
            <li><a class="treeview-item" href="/learning/courses/featured"><i class="icon fa fa-circle-o"></i> Featured Courses</a></li>
            <li><a class="treeview-item" href="/learning/courses/categories"><i class="icon fa fa-circle-o"></i> Course Categories</a></li>
            <li><a class="treeview-item" href="/learning/courses/types"><i class="icon fa fa-circle-o"></i> Course Types</a></li>
            <li><a class="treeview-item" href="#"><i class="icon fa fa-circle-o"></i> Quizes</a></li>
            <li><a class="treeview-item" href="#"><i class="icon fa fa-circle-o"></i> Certifiates</a></li>
            <li><a class="treeview-item" href="#"><i class="icon fa fa-circle-o"></i> Assignments</a></li>
            <li><a class="treeview-item" href="#"><i class="icon fa fa-circle-o"></i> Forums</a></li>
            <li><a class="treeview-item" href="#"><i class="icon fa fa-circle-o"></i> Enrollment</a></li>
            <li><a class="treeview-item" href="/learning/courses/reviews"><i class="icon fa fa-circle-o"></i> Reviews</a></li>
            <li><a class="treeview-item" href="/learning/courses/comments"><i class="icon fa fa-circle-o"></i> Comments</a></li>
            <li><a class="treeview-item" href="/learning/courses/discounts"><i class="icon fa fa-circle-o"></i> Discounts</a></li>
            <li><a class="treeview-item" href="#"><i class="icon fa fa-circle-o"></i> Packages</a></li>
            <li><a class="treeview-item" href="/learning/blogs"><i class="icon fa fa-circle-o"></i> Blogs</a></li>
            <li><a class="treeview-item" href="/learning/blogs/categories"><i class="icon fa fa-circle-o"></i> Blog Categories</a></li>
            <li><a class="treeview-item" href="/learning/blogs/comments"><i class="icon fa fa-circle-o"></i> Blogs Comments</a></li>


        </ul>
    </li> -->
    <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-cogs"></i><span class="app-menu__label">Settings</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">

            <li><a class="treeview-item" href="/messages"><i class="icon fa fa-circle-o"></i> Messages</a>
            </li>
            <li><a class="treeview-item" href="/settings"><i class="icon fa fa-circle-o"></i> Uploads</a>
            </li>
            <li><a class="treeview-item" href="/chats"><i class="icon fa fa-circle-o"></i> Chat Room</a>
            </li>
            <li><a class="treeview-item" href="/category"><i class="icon fa fa-circle-o"></i> Categories</a>
            </li>
            <li><a class="treeview-item" href="/loantypes"><i class="icon fa fa-circle-o"></i> Loan
                    Types</a></li>
            <li><a class="treeview-item" href="/savings/products"><i class="icon fa fa-circle-o"></i>
                    Savings Products</a></li>
            <li><a class="treeview-item" href="/settings/variables"><i class="icon fa fa-circle-o"></i>
                    Variables</a></li>
            <li><a class="treeview-item" href="/faqs"><i class="icon fa fa-circle-o"></i> FAQs</a>
            </li>
            <li><a class="treeview-item" href="/branches"><i class="icon fa fa-circle-o"></i> Branches</a>
            </li>

        </ul>
    </li>

</ul>