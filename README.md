 config.vm.provider "virtualbox" do |vb|
  #   # Display the VirtualBox GUI when booting the machine
  #   vb.gui = true
  #
  #   # Customize the amount of memory on the VM:
  #   vb.memory = "1024"
  # end

Install Project
=================
1. Install virtualBox + Vagrant
2. cd /path/project
3. vagrant up
4. edit hosts (192.168.3.15 project.int)
The project is available here: http://project.int/

Now you can work with virtual machine in vagrant ssh


Implement web application for files upload and their folders categorization and resulting view on separate page.

Web application consists of 2 sections:
---------------------------------------
• manager console with CRUD editors for categories/folders and files (aka backend)
• resulting view of categories and files (aka frontend)

Conditions:
-----------
• each file should belong to one of the given categories
• each category should be represented both in DB and file structure as folders with actual nesting structure of categories
• file should reside physically in specified folder that represents referenced category
• each folder should have at most 10 files
• authorization is not required (but is a plus)
• visual interface, libraries up to developer to decide
• maximum nesting level of categories is 5
• when file being clicked on frontend we should be see/download contents of file
• only text files are allowed to be uploaded
• use symfony2 framework
• use MySQL
• you are free to ask questions and some conditions of task can be changed upon mutual agreement