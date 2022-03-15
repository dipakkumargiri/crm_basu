<div class="white-box">
    <nav>
        <ul class="showClientTabs">
            <li class="clientProfile"><a href="{{ route('member.show_buyer', $client->id) }}"><i class="icon-user"></i> <span>@lang('modules.employees.profile')</span></a>
            </li>
            <!--<li class="clientProjects"><a href="{{ route('admin.clients.projects', $client->id) }}"><i class="icon-layers"></i> <span>@lang('app.menu.projects')</span></a>
            </li>
            <li class="clientInvoices"><a href="{{ route('admin.clients.invoices', $client->id) }}"><i class="icon-doc"></i> <span>@lang('app.menu.invoices')</span></a>
            </li>
            <li class="clientContacts"><a href="{{ route('admin.contacts.show', $client->id) }}"><i class="icon-people"></i> <span>@lang('app.menu.contacts')</span></a>
            </li>
            <li class="clientPayments"><a href="{{ route('admin.clients.payments', $client->id) }}"><i class="ti-receipt"></i> <span>@lang('app.menu.payments')</span></a>
            </li>-->

            <li class="clientDocs"><a href="{{ route('member.buyer_doucument', $client->id) }}"><i class="icon-docs"></i> <span>Document</span></a>
            </li>
          <!--  <li class="clientNotes"><a href="#"><i class="fa fa-sticky-note-o"></i> <span>@lang('app.menu.notes')</span></a>-->
          
           
        </ul>
    </nav>
</div>