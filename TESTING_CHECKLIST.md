# Testing Checklist - OMDB Movie App

Use this checklist to test all features of the application.

## 🔐 Authentication Testing

### Login Page
- [ ] Navigate to `http://localhost:8000`
- [ ] Confirm login page displays correctly
- [ ] UI elements present:
  - [ ] Username input field
  - [ ] Password input field
  - [ ] Remember me checkbox
  - [ ] Login button
  - [ ] Test credentials displayed

### Login Functionality
- [ ] **Valid Login**
  - Username: `aldmic`
  - Password: `123abc123`
  - [ ] Successfully redirects to movies page
  - [ ] Session is maintained

- [ ] **Invalid Login**
  - [ ] Wrong username shows error message
  - [ ] Wrong password shows error message
  - [ ] Empty fields show validation errors

### Logout
- [ ] Logout button visible in navbar
- [ ] Click logout redirects to login page
- [ ] Session is destroyed
- [ ] Cannot access protected pages after logout

---

## 🎬 Movie Browsing Testing

### Initial Load
- [ ] Movies list loads automatically with default search "movie"
- [ ] Movie cards display correctly with:
  - [ ] Movie poster
  - [ ] Movie title
  - [ ] Year
  - [ ] Type (movie/series/episode)
  - [ ] Favorite button (heart icon)
  - [ ] View details button

### Search Functionality
- [ ] **Search by Title**
  - [ ] Search for "Batman"
  - [ ] Results update correctly
  - [ ] Search for "Avengers"
  - [ ] Results update correctly
  - [ ] Search for nonexistent movie
  - [ ] Empty state displays

- [ ] **Filter by Type**
  - [ ] Select "Movie"
  - [ ] Only movies show
  - [ ] Select "Series"
  - [ ] Only series show
  - [ ] Select "Episode"
  - [ ] Only episodes show

- [ ] **Filter by Year**
  - [ ] Enter year "2020"
  - [ ] Only 2020 movies show
  - [ ] Enter year "1990"
  - [ ] Only 1990 movies show

- [ ] **Combined Filters**
  - [ ] Search "Batman" + Type "Movie" + Year "2008"
  - [ ] Should find "The Dark Knight"

### Infinite Scroll
- [ ] Scroll to bottom of page
- [ ] Loading spinner appears
- [ ] More movies load automatically
- [ ] Can scroll through multiple pages
- [ ] Stops loading when no more results

### Lazy Loading
- [ ] Open browser DevTools Network tab
- [ ] Scroll through movies
- [ ] Images load only when visible
- [ ] Placeholder images show before loading

---

## 📽️ Movie Details Testing

### Navigation
- [ ] Click "View Details" on any movie
- [ ] Redirects to detail page
- [ ] Correct movie information displays

### Information Display
- [ ] **Movie poster** loads correctly
- [ ] **Title** displayed prominently
- [ ] **Badges** show (Year, Rating, Runtime)
- [ ] **IMDB Rating** shows with stars
- [ ] **Genre** displayed
- [ ] **Director** name shown
- [ ] **Actors** listed
- [ ] **Writer** listed
- [ ] **Language** shown
- [ ] **Country** shown
- [ ] **Awards** displayed (if available)
- [ ] **Box Office** shown
- [ ] **Plot** description readable
- [ ] **Ratings** from different sources (if available)

### Navigation Buttons
- [ ] "Back to List" button returns to movies page
- [ ] Maintains search state when returning

---

## ❤️ Favorites Testing

### Add to Favorites
- [ ] **From Movie List**
  - [ ] Click empty heart icon on a movie
  - [ ] Heart fills (becomes solid)
  - [ ] Success message appears
  - [ ] Movie added to favorites

- [ ] **From Movie Details**
  - [ ] Click "Add to Favorites" button
  - [ ] Button changes to "Remove from Favorites"
  - [ ] Success message appears

### View Favorites
- [ ] Click "My Favorites" in navbar
- [ ] All favorited movies display
- [ ] Shows count badge
- [ ] Each favorite shows:
  - [ ] Movie poster
  - [ ] Movie title
  - [ ] Date added
  - [ ] Delete button (trash icon)
  - [ ] View details button

### Remove from Favorites
- [ ] **From Favorites Page**
  - [ ] Click trash icon
  - [ ] Confirmation dialog appears
  - [ ] Confirm deletion
  - [ ] Movie removed from list
  - [ ] Success message appears

- [ ] **From Movie List**
  - [ ] Click filled heart icon
  - [ ] Heart becomes empty
  - [ ] Movie removed from favorites

- [ ] **From Movie Details**
  - [ ] Click "Remove from Favorites" button
  - [ ] Button changes to "Add to Favorites"
  - [ ] Success message appears

### Empty State
- [ ] Remove all favorites
- [ ] Empty state displays with:
  - [ ] Broken heart icon
  - [ ] "No Favorites Yet" message
  - [ ] "Browse Movies" button

---

## 🌐 Multi-Language Testing

### Language Switcher
- [ ] Language switcher visible in navbar
- [ ] Shows "EN" and "ID" options
- [ ] Active language highlighted

### Switch to Indonesian
- [ ] Click "ID" button
- [ ] All static text changes to Indonesian:
  - [ ] Navigation menu
  - [ ] Search form labels
  - [ ] Button text
  - [ ] Messages and notifications
  - [ ] Empty states
- [ ] Movie data (from API) remains in original language

### Switch to English
- [ ] Click "EN" button
- [ ] All static text changes to English
- [ ] Language preference persists across pages

### Language Persistence
- [ ] Select Indonesian
- [ ] Navigate to different pages
- [ ] Language stays Indonesian
- [ ] Logout and login again
- [ ] Language resets to default English

---

## 📱 Responsive Design Testing

### Desktop (1920x1080)
- [ ] Layout looks proper
- [ ] No horizontal scrolling
- [ ] All elements visible
- [ ] 4 movies per row

### Tablet (768x1024)
- [ ] Layout adjusts properly
- [ ] 2 movies per row
- [ ] Navbar collapses to hamburger
- [ ] All features accessible

### Mobile (375x667)
- [ ] 1 movie per row
- [ ] Touch-friendly buttons
- [ ] Forms easy to fill
- [ ] Scrolling smooth
- [ ] All features work

---

## 🎨 UI/UX Testing

### Visual Design
- [ ] Consistent color scheme (purple gradient)
- [ ] Proper shadows and depth
- [ ] Smooth hover effects
- [ ] Icons display correctly (Font Awesome)
- [ ] Bootstrap components render properly

### Loading States
- [ ] Loading spinner shows during API calls
- [ ] Skeleton/placeholder for images
- [ ] No broken images

### Error States
- [ ] Empty search results show message
- [ ] API errors show user-friendly message
- [ ] Failed login shows error
- [ ] Network errors handled gracefully

### Notifications
- [ ] Success messages appear (green)
- [ ] Error messages appear (red)
- [ ] Messages auto-dismiss after 3 seconds
- [ ] Messages don't overlap

---

## 🔒 Security Testing

### Authentication
- [ ] Cannot access `/movies` without login
- [ ] Cannot access `/favorites` without login
- [ ] Login required redirects to login page
- [ ] After login, redirects to intended page

### CSRF Protection
- [ ] All forms have CSRF token
- [ ] Forms fail without valid token
- [ ] Token regenerates after logout

### Session Management
- [ ] Session expires after inactivity (if configured)
- [ ] "Remember me" works correctly
- [ ] Multiple tabs/windows share session

### Input Validation
- [ ] SQL injection attempts fail
- [ ] XSS attempts sanitized
- [ ] Special characters handled properly

---

## ⚡ Performance Testing

### Page Load Speed
- [ ] Login page loads quickly
- [ ] Movies list loads within 2 seconds
- [ ] Detail page loads quickly
- [ ] No unnecessary delays

### API Performance
- [ ] API calls complete within reasonable time
- [ ] Multiple requests don't block UI
- [ ] Failed requests don't crash app

### Memory Usage
- [ ] Infinite scroll doesn't cause memory leaks
- [ ] Images released from memory when scrolled past
- [ ] No console errors or warnings

---

## 🐛 Edge Cases Testing

### Empty/Null Data
- [ ] Movie with no poster shows placeholder
- [ ] Movie with missing data shows "N/A"
- [ ] Empty search term handled

### Special Characters
- [ ] Search for "Amélie"
- [ ] Search for "ñ" characters
- [ ] Unicode characters display correctly

### Network Issues
- [ ] Disconnect network during search
- [ ] Reconnect and retry
- [ ] Graceful error handling

### Browser Compatibility
- [ ] **Chrome** - All features work
- [ ] **Firefox** - All features work
- [ ] **Edge** - All features work
- [ ] **Safari** - All features work (if available)

---

## ✅ Final Checklist

- [ ] All core features working
- [ ] No console errors
- [ ] No broken images
- [ ] All links work
- [ ] Forms validate properly
- [ ] API integration works
- [ ] Database operations successful
- [ ] Multi-language works
- [ ] Responsive on all devices
- [ ] Security measures in place
- [ ] Performance acceptable
- [ ] Code is clean and documented
- [ ] README is complete
- [ ] Installation guide is accurate

---

## 📊 Test Results Summary

**Date Tested**: _______________

**Tested By**: _______________

**Browser**: _______________

**Total Tests**: _______________

**Passed**: _______________

**Failed**: _______________

**Pass Rate**: _______________%

### Issues Found:

1. _______________________________________________
2. _______________________________________________
3. _______________________________________________

### Notes:

_______________________________________________
_______________________________________________
_______________________________________________
