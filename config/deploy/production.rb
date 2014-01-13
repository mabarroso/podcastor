set_deploy 'prod'

role :web, 'lab.mabarroso.com'
role :app, 'lab.mabarroso.com'
role :db, 'lab.mabarroso.com', :primary => true
