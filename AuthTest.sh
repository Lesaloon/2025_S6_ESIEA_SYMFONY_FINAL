#!/bin/bash

read -p "Press Y to run the script without purging the database or P to purge the database: " purge_db
if [ "$purge_db" == "P" ]; then
  echo -e "\e[32mPurging the database...\e[0m"
  php bin/console doctrine:fixtures:load --purge-with-truncate
  if [ $? -ne 0 ]; then
    echo -e "\e[31mFailed to purge the database. Please check your credentials.\e[0m"
    exit 1
  fi
fi

# Step 1: Login and get JWT token
echo "Logging in as admin..."

LOGIN_RESPONSE=$(curl -s -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@airsoft.local","password":"admin123"}')

# Step 2: Extract the token using grep/sed
ADMIN_TOKEN=$(echo "$LOGIN_RESPONSE" | sed -n 's/.*"token":"\([^"]*\)".*/\1/p')

if [ -z "$ADMIN_TOKEN" ]; then
  echo -e "\e[31mFailed to log in. Please check your credentials.\e[0m"
  echo -e "\e[31mResponse\e[0m $LOGIN_RESPONSE"
  exit 1
fi

echo -e "\e[32mSuccessfully logged in!\e[0m"
echo -e "\e[32mToken\e[0m: $ADMIN_TOKEN"

# Step 3: Use token to access a secure route

echo -e "\e[32mRequesting /api/secure/admin with token...\e[0m"

curl -X GET http://localhost:8000/admin \
  -H "Authorization: Bearer $ADMIN_TOKEN"

# Step 4 : Use token to access a non-secure route
echo -e "\n\e[32mRequesting /api/secure/user with token...\e[0m"

curl -X GET http://localhost:8000/public \
  -H "Authorization: Bearer $ADMIN_TOKEN"

read -p "Press y to create a new user or any other key to exit: " create_user
if [ "$create_user" == "y" ]; then

  read -p "Press y to enter the new user details or any other key to use default values: " enter_user_details
  if [ "$enter_user_details" == "y" ]; then
    read -p "Enter the email of the new user: " new_user_email
    read -p "Enter the password of the new user: " new_user_password
  else
    # Default values
    new_user_email="user@airsoft.local"
    new_user_password="user123"
  fi

  # Step 5: Create a new user
  echo -e "\e[32mCreating a new user...\e[0m"

  REGISTER_RESPONSE=$(curl -X POST http://localhost:8000/api/register \
    -H "Content-Type: application/json" \
    -d '{
      "email": "'"$new_user_email"'",
      "password": "'"$new_user_password"'"}')

  USER_TOKEN=$(echo "$REGISTER_RESPONSE" | sed -n 's/.*"token":"\([^"]*\)".*/\1/p')
  if [ -z "$USER_TOKEN" ]; then
    echo -e "\e[31mFailed to create user.\e[0m"
    echo -e "\e[31mResponse\e[0m $REGISTER_RESPONSE"
    STATUS=$(echo "$REGISTER_RESPONSE" | sed -n 's/.*"status":"\([^"]*\)".*/\1/p')
    echo -e "\e[31m$STATUS\e[0m"

    if [ "$STATUS" == "ACCOUNT_CREATED_WAITING_ADMIN_VALIDATION" ]; then
      echo -e "\e[32m User created but not activated.\e[0m"
      echo -e "\e[32mListing unapproved users...\e[0m"
      UNAPPROVED_USERS=$(curl -s -X GET http://localhost:8000/api/admin/unapproved-users \
        -H "Authorization: Bearer $ADMIN_TOKEN")

      echo "$UNAPPROVED_USERS"

      # Extract user ID by email
      NEW_USER_ID=$(echo "$UNAPPROVED_USERS" | grep -oP '{"id":\K[0-9]+(?=,"email":"'"$new_user_email"')')

      if [ -z "$NEW_USER_ID" ]; then
        echo -e "\e[31mCould not find unapproved user ID for $new_user_email\e[0m"
        exit 1
      fi

      echo -e "\e[32mApproving user ID: $NEW_USER_ID...\e[0m"
      APPROVE_RESPONSE=$(curl -s -X POST http://localhost:8000/api/admin/approve/$NEW_USER_ID \
        -H "Authorization: Bearer $ADMIN_TOKEN")

      echo -e "\e[32mApproval response:\e[0m $APPROVE_RESPONSE"
      echo ""
      if [ $? -ne 0 ]; then
        echo -e "\e[31mFailed to activate user. Please check your credentials.\e[0m"
        exit 1
      fi
      echo -e "\e[32mUser activated!\e[0m"
      echo -e "\e[32mLogging in as new user...\e[0m"
      USER_TOKEN=$(curl -s -X POST http://localhost:8000/api/login \
        -H "Content-Type: application/json" \
        -d '{"email":"'"$new_user_email"'","password":"'"$new_user_password"'"}' | sed -n 's/.*"token":"\([^"]*\)".*/\1/p')
      if [ -z "$USER_TOKEN" ]; then
        echo -e "\e[31mFailed to log in as new user. Please check your credentials.\e[0m"
        echo -e "\e[31mResponse\e[0m $LOGIN_RESPONSE"
        exit 1
      fi
      echo -e "\e[32mSuccessfully logged in as new user!\e[0m"
      echo -e "\e[32mToken\e[0m: $USER_TOKEN"
    else
      exit 1
    fi
  fi
  echo -e "\e[32mSuccessfully created user!\e[0m"
  # echo -e "\e[32mRequesting /admin with token...\e[0m"
  # curl -X GET http://localhost:8000/admin \
  #  -H "Authorization: Bearer $USER_TOKEN"
  echo -e "\n\e[32mRequesting /user with token...\e[0m"
  curl -X GET http://localhost:8000/user \
    -H "Authorization: Bearer $USER_TOKEN"
  echo -e "\n\e[32mRequesting /public with token...\e[0m"
  curl -X GET http://localhost:8000/public \
    -H "Authorization: Bearer $USER_TOKEN"

  # Step 6: Use token to get /api/me
  # echo -e "\n\e[32mRequesting /api/me with token...\e[0m"
  # curl -X GET http://localhost:8000/api/me \
  #   -H "Authorization $USER_TOKEN"

fi