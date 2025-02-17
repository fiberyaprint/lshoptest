TemplateNotFound
jinja2.exceptions.TemplateNotFound: form.html

Traceback (most recent call last)
File "flask\app.py", line 1536, in __call__
File "flask\app.py", line 1514, in wsgi_app
File "flask\app.py", line 1511, in wsgi_app
File "flask\app.py", line 919, in full_dispatch_request
File "flask\app.py", line 917, in full_dispatch_request
File "flask\app.py", line 902, in dispatch_request
File "app.py", line 35, in index
File "flask\templating.py", line 149, in render_template
File "jinja2\environment.py", line 1087, in get_or_select_template
File "jinja2\environment.py", line 1016, in get_template
File "jinja2\environment.py", line 975, in _load_template
File "jinja2\loaders.py", line 126, in load
File "flask\templating.py", line 65, in get_source
File "flask\templating.py", line 99, in _get_source_fast
jinja2.exceptions.TemplateNotFound: form.html
This is the Copy/Paste friendly version of the traceback.

Traceback (most recent call last):
  File "flask\app.py", line 1536, in __call__
  File "flask\app.py", line 1514, in wsgi_app
  File "flask\app.py", line 1511, in wsgi_app
  File "flask\app.py", line 919, in full_dispatch_request
  File "flask\app.py", line 917, in full_dispatch_request
  File "flask\app.py", line 902, in dispatch_request
  File "app.py", line 35, in index
  File "flask\templating.py", line 149, in render_template
  File "jinja2\environment.py", line 1087, in get_or_select_template
  File "jinja2\environment.py", line 1016, in get_template
  File "jinja2\environment.py", line 975, in _load_template
  File "jinja2\loaders.py", line 126, in load
  File "flask\templating.py", line 65, in get_source
  File "flask\templating.py", line 99, in _get_source_fast
jinja2.exceptions.TemplateNotFound: form.html

The debugger caught an exception in your WSGI application. You can now look at the traceback which led to the error. If you enable JavaScript you can also use additional features such as code execution (if the evalex feature is enabled), automatic pasting of the exceptions and much more.
Brought to you by DON'T PANIC, your friendly Werkzeug powered traceback interpreter.
Console Locked
The console is locked and needs to be unlocked by entering the PIN. You can find the PIN printed out on the standard output of your shell that runs the server.

PIN: 
 
