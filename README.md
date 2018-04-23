# PWGRAM

**Author**: Marc Millán Gimeno - ls29307 

## 1. Introduction

The practical final of Projects Web treats to design a platform web that emulate the operation of the already known social network of *Instagram. 
Like this then, our practical has to fulfil with different functionalities that include management of users registered to the network, management of the images that the users use *y the 
interaction between the different users and the images that have gone hanging and modifying.

The management of the users begins with the design of a register that validate the information of the user and allow us insert users to the *bbdd. Once the user was registered will be able to also see his personal page and he will have the option to modify his data. 

The images are also a very important part to the platform and therefore will be able to find them to almost all the functionalities of this. The first that uses  is the to add a new image, the user enters the data to a form and this validates them and inserts the image to the *bbdd in 2 different sizes (400*x300 and 100*x100). The images 
also will be able to  edit later for the user that has inserted them and the rest of users will be able to *interactuar with them.

The last big *apartat is the one of the interaction of the users with the images. This bases in which the users can insert comments (one for photo) and give or take out *likes to 
the images that have hanged  to the network.

## 2. Explanation of the blocks

All the *Controllers and *Services have the already designed names because they correspond with his functionalities, like this then all the bowls related with images will find them to *ImageController and *ImageService, all the one of profile to *PerfilController and *PerfilService and like this with the rest of functionalities. 

### 2.1. Register:
The function register initiates the process of register all *renderitzant the *twig. Next we employ the function *validateUser to validate the data, the function *activeUser and *sendMail to actuate the user and send him the mail of confirmation and for the database use the functions *AddUser and *upUserToDataBase. 
 
### 2.2. LogIn:
The function *LoginRequest is what is us what determines if the user that tries loggin  valid or no and will leave it go in or will show a message of error. 
 
### 2.3. Edition of the Profile:
The functions of *EditProfileAction that will serve us for rendering the form, the one of *ConfirmProfileAction that use to check the fields of the form once the user has *clicat *submit and if all is correct, update the information of the user and the function *ResizeImages that receive the information of the image (*path and *name) and the new sizes that want to and does the *resize of the image if the user wants  change the photo of profile. 
 
### 2.4. Add a new image:
Will use the functions *InsertImageAction that us rendering the form to enter the image, the function *NewImageAction that serves to validate the data entered by the user and enter the image to the *bbdd in case that was all correct and finally the function *ResizeImages that receive the information of the image (*path and *name) and the new sizes that want to and does the *resize of the image if the user wants  change the photo of profile. 
 
### 2.5. Edit and delete an existent image:
The functions employed in that case are, *EditImageAction that will allow us rendering the data of the image that has selected . *UpdateImageAction That will serve to validate the data of the new image and in case that was all correct to update the data *ResizeImages to create the images with the corresponding sizes (400*x300 and 100*x100) and *RemoveImageAction that will allow us delete the image of the folders and all the tables of the *bbdd. 
 
### 2.6. Visualisation of the public images:
This block has only of a function, *ViewImageAction, that us *renderitzarà the page with the information of the image. 
 
### 2.7. Public profile of an user:
This block find it implemented to the function of *ViewProfileAction that will show us the data of the user as well as the images that has hanged. 
 
### 2.8. Comments of the images:
In this block have implemented the functions because the user can create a comment, edit it (*editComment and *updateComment) and erase it (*deleteComment). 
 
### 2.9. *Likes Of the images:
To implement the functionality of the *likes use the function *newlikeHome (nine *like to an image) and *delatelikeHome (delete *like of the image).

### 2.10. Notifications: 
This block features only of 2 functions, *showNotifications that it is to show the list of the notifications and *delateNotifications that will allow us erase the notifications. 
 
### 2.11. Home:
This functionality only has a function. *GoHomeAction Allow us *rendering the *twig of the man where will find all the specifications that ask . 
 
### 2.12. Images more seen:
This functionality find it in the function of the man, where have done a *select to order all the *posts that have done  as the date of creation and next have sent the result of this *select to the *twig.

**Delivery date** : 18/05/2017
 
**Collaborators:**

   - Albert Bosch Pascual  ls28932
   - Albert Martí Rolland  ls28855
