package com.manateeworks.CNICScanner;

public class ClassSearchUser {

  private String name;
  private boolean selected;
  private ClassUser User;

  public ClassSearchUser(String name) {
    this.name = name;
    selected = false;
  }
  public ClassSearchUser(ClassUser user) {
	    this.name =user.IDno +"\n"+ user.Name;
	    selected = false;
	    User=user;
	  }
  public ClassSearchUser(ClassUser user,boolean userselected) {
	    this.name =user.IDno +"\n"+ user.Name;
	    selected = userselected;
	    User=user;
	  }
  public String getName() {
	  
	 // return  User.IDno+ User.Name;
    return name;
  }
  public ClassUser geUser() {
	  return User;
}
  public void setName(String name) {
    this.name = name;
  }

  public boolean isSelected() {
    return selected;
  }

  public void setSelected(boolean selected) {
    this.selected = selected;
  }

} 