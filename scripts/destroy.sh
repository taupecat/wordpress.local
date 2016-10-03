#!/bin/bash

mysqldump --user=root --password=password rp3_site_dev > /vagrant/scripts/database.sql
