+++
title = "Resolving Double Render Errors in Rails"
summary = "Prevent Rails from throwing errors when including two redirects in an action"
date = "2020-04-10"
tags = ["rails"]
draft = false
+++

When using `redirect_to` or `render` twice in one action, we get the `AbstractController::DoubleRenderError` error.

For example, I had the following action with two `redirect_to` methods:

```rb
def create
  if @user.has_statement_this_month?(@account)
    flash[:alert] = "You already have a statement for this month."
    redirect_to(account_path(@account))
  end

  @statement = Statement.new(statement_params)
  @statement.account_id = @account.id

  if @statement.save
    flash[:notice] = 'Statement created'
    redirect_to(account_path(@account))
  else
    flash[:alert] = @statement.errors.full_messages.join(', ')
    render('new')
  end
end
```

In this case, if a statement has an existing statement record for the current month, the action should redirect the user to `account_path`. This part works. However, redirects *do not* stop execution of the remaining code. This means that in the code above, it is possible to try to redirect to `account_path` as well as render the `new` template. This results in an error: `AbstractController::DoubleRenderError`.

To resolve this, we simply add a `return` statement after the `redirect_to` in order to exit out of the action:

```rb
if @user.has_statement_this_month?(@account)
  flash[:alert] = "You already have a statement for this month."
  redirect_to(account_path(@account)) && return
end
```

Sometimes I forget that actions are just regular ruby methods and can be exited out of by using `return`.

More information is available here: [Avoiding Double Render Errors](https://guides.rubyonrails.org/layouts_and_rendering.html#avoiding-double-render-errors)