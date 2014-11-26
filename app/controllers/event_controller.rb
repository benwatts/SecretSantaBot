class EventController < ApplicationController
  def new
    @people = [
      {
        "name" => "brandeen",
        "email" => "@",
      },
      {
        "name" => "bertha",
        "email" => "@",
        "avoid" => "horace"
      },
      {
        "name" => "horace",
        "email" => "@",
        "avoid" => "bertha"
      },
      {
        "name" => "clarice",
        "email" => "@",
        "avoid" => "dirk"
      },
      {
        "name" => "dirk",
        "email" => "@",
        "avoid" => "clarice"
      },
      {
        "name" => "obamaniqua",
        "email" => "@",
        "avoid" => "fred"
      },
      {
        "name" => "fred",
        "email" => "@",
        "avoid" => "obamaniqua"
      },
      {
        "name" => "francium",
        "email" => "@",
      }
    ]

    pairPeople
    puts @people
  end

  def pairPeople
    @receiving_a_gift = []

    @people.each do |person|
      person.delete 'giving_to'
      pool = generatePoolFor person
      assign_to = pool.sample
      person['giving_to'] = assign_to['name']
      @receiving_a_gift.push assign_to
    end
  end

  def generatePoolFor(person)
    name_to_avoid = person['avoid']
    pool  = @people.clone - [person] - @receiving_a_gift

    return pool if !name_to_avoid

    pool.each do |person_to_avoid|
      return pool - [person_to_avoid] if person_to_avoid['name'] == name_to_avoid
    end

    return pool
  end

end
